<?php


namespace rednaoeasycalculationforms\Managers\QueryManager;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\DTO\ConditionBaseOptionsDTO;
use rednaoeasycalculationforms\DTO\FilterConditionOptionsDTO;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\ObjectQueryFormatter;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\QueryFormatterBase;
use rednaoeasycalculationforms\Managers\QueryManager\WhereGroup;
use rednaoeasycalculationforms\Managers\QueryManager\WhereStatement;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\CheckBoxComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ColumnComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\NumericComparisonFormatter;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\DateFixedValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\EntryIdValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\FixedValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ListFieldFixedValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ListFixedValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\MultipleValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\OrValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\UserValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Filters\FilterGroup;
use rednaoeasycalculationforms\pr\QueryBuilder\Filters\FilterLineBase;
use rednaoeasycalculationforms\pr\QueryBuilder\Filters\MultipleGroupFilter;
use rednaoeasycalculationforms\pr\QueryBuilder\QueryBuilder;
use rednaoeasycalculationforms\pr\QueryBuilder\QueryElement\Dependency;

class QueryManager
{
    public $LastQuery;
    public $FormId;
    /** @var QueryBuilder */
    public $QueryBuilder;
    public $QueryFormatter;
    /** @var Loader */
    public $Loader;
    /** @var String[] */
    public $Columns;
    /** @var DBManager $DBManager */
    public $DBManager;
    /** @var WhereGroup[] */
    public $WhereGroups;
    /** @var FormBuilder */
    public $FormBuilder;
    public $SortItems=[];
    public function __construct($loader,$formId,$queryFormatter=null)
    {
        $repository=new FormRepository($loader);
        $form=$repository->GetForm($formId)->Options;
        $this->Loader=$loader;
        if($form!=null) {
            $this->FormBuilder = new FormBuilder($this->Loader, $form, null);
            $this->FormBuilder->Initialize();
        }
        $this->WhereGroups=[];
        $this->Columns=[];
        $this->FormId=$formId;
        if($queryFormatter==null)
            $this->QueryFormatter=new ObjectQueryFormatter($this);

        $this->QueryBuilder=new QueryBuilder($this->Loader,$this->Loader->RECORDS_TABLE,'ROOT');
    }

    public function CreateWhereGroup(){
        $whereGroup=new WhereGroup($this);
        $this->WhereGroups[]=$whereGroup;

        return $whereGroup;
    }

    public function AddSort($fieldId,$pathId='',$orientation='ASC')
    {
        $this->SortItems[] =(Object)[
            "FieldId"=>$fieldId,
            "PathId"=>$pathId,
            "Orientation"=>$orientation
        ];
    }

    public function GetResult()
    {
        $results=$this->GetResults();
        if(count($results)>0)
            return $results[0];

        return null;
    }
    public function GetResults($limit=-1, $skip=-1)
    {
        $columns=$this->Columns;
        if(\count($this->Columns)==0)
        {
            $columns[]='user_id';
            $columns[]='sequence';
            $columns[]='formatted_sequence';
            $columns[]='date';
            $columns[]='data';
            $columns[]='total';
            $columns[]='status';
            $columns[]='form_id';
            $columns[]='entry_id';
            $columns[]='reference_id';
            $columns[]='meta_values';
        }

        foreach($columns as &$currentColumn)
        {
            $currentColumn='record.'.$currentColumn;
        }


        $queryBuilder=$this->CreateQueryBuilder();
        $results=$queryBuilder->GetRows($limit,$skip);
        $this->LastQuery=$queryBuilder->LastQuery;

        $rowsToReturn=array();

        foreach($results as $currentResult)
        {
            $currentResult->data=\json_decode($currentResult->data);
            if($this->QueryFormatter->CanAddMultipleRows())
                $rowsToReturn=array_merge($rowsToReturn,$this->QueryFormatter->FormatRow($currentResult));
            else
                $rowsToReturn[] = $this->QueryFormatter->FormatRow($currentResult);
        }

        return $this->QueryFormatter->PostProcess($rowsToReturn);

    }

    private function CreateQueryBuilder(){
        $queryBuilder=new QueryBuilder($this->Loader,$this->Loader->RECORDS_TABLE,'ROOT');
        $this->QueryBuilder=$queryBuilder;
        $queryBuilder->SortItems=$this->SortItems;
        $filterGroup=new FilterGroup($queryBuilder,'and');
        $queryBuilder->Filters[]=$filterGroup;
        $line=$filterGroup->CreateFilterLine();
        $line->Filter=new FixedValueComparison('ROOT','form_id','Equal',$this->FormId);
        foreach($this->WhereGroups as $currentGroup)
        {
            $multiple=new MultipleGroupFilter();
            $filterGroup=new FilterGroup($queryBuilder,'and');
            foreach($currentGroup->Statements as $line)
            {
                $this->GenerateQueryElements($filterGroup,$line);
            }
            $queryBuilder->Filters[]=$filterGroup;
        }



        return $queryBuilder;
    }

    /**
     * @param $filterGroup FilterGroup
     * @param $line WhereStatement
     * @return void
     * @throws FriendlyException
     */
    public function GenerateQueryElements($filterGroup,$line){
        if($line->FieldId==''||$line->Comparator=='None')
            return;

        if($line->FieldId=='_sequence')
        {
            $filterLine=$filterGroup->CreateFilterLine();
            $filterLine->Filter=new ListFixedValueComparison('ROOT','sequence',$this->Loader->RECORDS_TABLE,'sequence',$line->Comparator,$line->Value);
            return;
        }

        if($line->FieldId=='_is_visible')
        {
            $filterLine=$filterGroup->CreateFilterLine();
            $filterLine->Filter=new ListFixedValueComparison('ROOT','is_visible',$this->Loader->RECORDS_TABLE,'is_visible',$line->Comparator,$line->Value);
            return;

        }


        if($line->FieldId=='_entryId')
        {
            $filterLine=$filterGroup->CreateFilterLine();
            $filterLine->Filter=new ListFixedValueComparison('ROOT','entry_id',$this->Loader->RECORDS_TABLE,'entry_id',$line->Comparator,$line->Value);
            return;
        }

        if($line->FieldId=='_date')
        {
            $filterLine=$filterGroup->CreateFilterLine();
            $filterLine->Filter=new DateFixedValueComparison('ROOT','date',$line->Comparator,$line->Value,null,'Date');
            return;
        }

        if($line->TableId!='ROOT')
        {
            $dependency=new Dependency($line->TableName,$line->TableId);
            $dependency->Comparisons[]=new ColumnComparison('ROOT','entry_id','Equal',$line->TableId,'entry_id');
            $dependency->Comparisons[]=new FixedValueComparison($line->TableId,'field_id','Equal',$line->FieldId);
            $this->QueryBuilder->AddDependency($dependency);
        }

        $filterLine=$filterGroup->CreateFilterLine();
        switch($line->SubType)
        {
            case 'Text':
            case 'Composed':
            case 'Standard':
                if($this->FormBuilder==null)
                    throw new FriendlyException('Form was not found, maybe it was deleted?');
                $column=$line->Column;
                if($line->FieldId!='')
                {
                    $field=$this->FormBuilder->GetFieldById($line->FieldId);
                    if($field==null)
                        break;

                    $column=$field->GetColumnById($line->PathId);
                }

                if(is_array($line->Value))
                {
                    $orComparator=new OrValueComparison();
                    $filterLine->Filter=$orComparator;
                    foreach($line->Value as $currentValue)
                    {
                        $orComparator->AddComparison(new FixedValueComparison($line->TableId,$column,$line->Comparator,$currentValue,null,$line->ValueSubType));
                    }
                }else
                    $filterLine->Filter=new FixedValueComparison($line->TableId,$column,$line->Comparator,$line->Value);
                break;
            case 'MultipleValues':
            case 'Survey':
                $valueToUse=$line->Value;
                if($line->Type=='Field')
                {
                    if($this->FormBuilder==null)
                        break;

                    $field=$this->FormBuilder->GetFieldOptionsById($line->FieldId);
                    if($field==null)
                        break;

                    if(!isset($field->Options))
                        break;

                    $valuesById=$valueToUse;
                    $valueToUse=[];
                    if(is_array($valuesById))
                        foreach($valuesById as $currentId)
                            foreach($field->Options as $currentOptions)
                            {
                                if($currentOptions->Id==$currentId||$currentOptions->Label==$currentId)
                                {
                                    $valueToUse[]=$currentOptions->Label;
                                }
                            }





                }
                $filterLine->Filter=new MultipleValueComparison($line->TableId,$line->FieldId,$line->TableName,$line->Column,$line->Comparator,$valueToUse,null,$line->ValueSubType);
                break;
            case 'Numeric':
            case 'Time':
                if($line->Column=='')
                    $column='numericvalue';
                $filterLine->Filter=new FixedValueComparison($line->TableId,$column,$line->Comparator,$line->Value,new NumericComparisonFormatter());
                break;
            case 'DateTime':
            case 'Date':
                $filterLine->Filter=new DateFixedValueComparison($line->TableId,$line->Column,$line->Comparator,$line->Value);
                break;
            case 'User':
                $filterLine->Filter=new UserValueComparison($filterLine, $line->TableId,$line->Column,$line->Comparator,$line->Value);
                break;
            case 'EntryId':
                $filterLine->Filter=new EntryIdValueComparison($filterLine, $line->TableId,$line->Column,$line->Comparator,$line->Value);
                break;
            case 'Checkbox':
                $filterLine->Filter=new CheckBoxComparison($line->TableId,$line->Column,$line->Comparator,$line->Value);
                break;
            case 'List':
                $filterLine->Filter=new ListFieldFixedValueComparison($line->TableId,$line->FieldId,$this->Loader->RECORDS_DETAIL,'value',$line->Comparator,$line->Value);
                break;
            case 'Role':
                $filterLine->Filter=new UserValueComparison($filterLine,$line->TableId,$line->Column,$line->Comparator,$line->Value);
                break;

            default:
                throw new FriendlyException('Invalid condition type '.$line->SubType.', please check the data source filters and make sure they are correct');
        }
    }


    public function GetCount()
    {

        $queryBuilder=$this->CreateQueryBuilder();
        $count= $queryBuilder->GetCount();
        $this->LastQuery=$queryBuilder->LastQuery;
        return $count;

    }

    /**
     * @param $condition ConditionBaseOptionsDTO
     */
    public function AddCondition($condition)
    {
        if($condition==null)
            return;
        foreach($condition->ConditionGroups as $conditionGroup)
        {
            $whereCondition=$this->CreateWhereGroup();
            foreach($conditionGroup->ConditionLines as $conditionLine)
            {
                $tableId=$conditionLine->FieldId.'_'.$conditionLine->PathId;
                $valueSubType='';
                if(isset($condition->ValueSubType))
                    $valueSubType=$condition->ValueSubType;
                $whereCondition->AddWhereStatement($conditionLine->FieldId,$conditionLine->Comparison,$conditionLine->Value,$this->Loader->RECORDS_DETAIL,$tableId,$conditionLine->PathId,$conditionLine->SubType,$valueSubType,$conditionLine->Type);
            }
        }
    }



}