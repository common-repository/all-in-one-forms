<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder;



use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ColumnComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\FixedValueComparison;
use rednaoeasycalculationforms\pr\QueryBuilder\Filters\FilterGroup;
use rednaoeasycalculationforms\pr\QueryBuilder\QueryElement\Dependency;
use rednaoeasycalculationforms\pr\QueryBuilder\QueryElement\QueryElement;

class QueryBuilder
{
    public $Level;
    /** @var QueryElement[] */
    public $Elements;
    public $RootTable;
    public $RootID;
    /** @var FilterGroup[] */
    public $RootConditions;
    /** @var Dependency[] */
    public $Dependencies;
    /** @var FilterGroup[] */
    public $Filters;
    public $LastQuery='';
    public $SortItems;
    /** @var string[] */
    public $Columns;
    public $NegateFilter;
    /** @var Loader */
    public $Loader;

    /**
     * QueryBuilder constructor.
     * @param $RootTable
     * @param $RootID
     * @param $rootConditions FilterGroup[]
     */
    public function __construct($Loader,$RootTable=null,$RootID=null,$rootConditions=[])
    {
        if($RootTable==null)
            $RootTable=$this->Loader->RECORDS_TABLE;
        if($RootID==null)
            $RootID='ROOT';
        $this->SortItems=[];
        $this->Loader=$Loader;
        if($rootConditions==null)
            $rootConditions=[];
        $this->RootTable=$RootTable;
        $this->RootID=$RootID;
        $this->RootConditions=$rootConditions;
        $this->Filters=[];
        $this->NegateFilter=false;
        $this->Elements=[];
        $this->Dependencies=[];
    }


    public function SetNegateFilter(){
        $this->NegateFilter=true;
    }


    /**
     * @param $queryElement QueryElement
     */
    public function AddQueryElement($queryElement)
    {
        $this->Elements[]=$queryElement;
    }

    public function Execute($limit=0,$skip=0)
    {

        $limitFilter='';
        if($limit>0)
        {
            $limitFilter=' limit ';

            if($skip>0)
                $limitFilter.=$skip.' , ';

            $limitFilter.=$limit;
        }
        $sortSection=$this->GenerateSorts();
        if($sortSection=='')
            $sortSection=' order by ROOT.date desc';
        $this->GenerateColumns();
        $filters=$this->GenerateFilterString();

        $query=$this->CreateQueryString();

        $query=$query.' '.$filters.$sortSection.$limitFilter;
        $this->LastQuery= $query;
        return $query;

    }

    public function GetCount()
    {
        $filters=$this->GenerateFilterString();
        $query=$this->CreateQueryString(true);

        $query=$query.' '.$filters;
        $this->LastQuery= $query;

        $dbmanager=new DBManager(true);
        return intval($dbmanager->GetVar($query));

    }

    public function GetRows($limit=0,$skip=0){
        $dbmanager=new DBManager(true);
        $result=$dbmanager->GetResults($this->Execute($limit,$skip));
        $this->LastQuery=$dbmanager->LastQuery;
        return $result;
    }


    private function GenerateColumns()
    {
        $this->Columns=[];
        foreach($this->Elements as $element)
        {
            foreach($element->Dependencies as $dependency)
            {
                if($this->HasDependency($dependency->Id))
                    continue;
                $this->Dependencies[]=$dependency;
            }
            foreach($element->Columns as $column)
            {
                $column=$column->CreateColumn();
                if(ArrayUtils::Find($this->Columns,function ($item)use($column){return $item==$column;})==null)
                    $this->Columns[]=$column;
            }

        }

        if(count($this->Columns)==0||count($this->Columns)==count($this->SortItems))
        {
            $this->Columns[]='ROOT.user_id';
            $this->Columns[]='ROOT.sequence';
            $this->Columns[]='ROOT.formatted_sequence';
            $this->Columns[]='ROOT.date';
            $this->Columns[]='ROOT.data';
            $this->Columns[]='ROOT.total';
            $this->Columns[]='ROOT.status';
            $this->Columns[]='ROOT.form_id';
            $this->Columns[]='ROOT.entry_id';
            $this->Columns[]='ROOT.reference_id';
            $this->Columns[]='ROOT.meta_values';
        }

    }

    public function HasDependency($dependency)
    {
        return ArrayUtils::Find($this->Dependencies,function ($item)use($dependency){
            return $dependency==$item->Id;
        })!=null;

    }




    private function GenerateFilterString()
    {
        $filters='';
        if(count($this->RootConditions)>0){
            foreach ($this->RootConditions as $filterGroup)
            {
                $filters.=' and '.$filterGroup->CreateGroupString();
            }
        }

        $andFilters='';
        $orFilters='';

        foreach($this->Filters as $filterGroup)
        {
            if(count($filterGroup->FilterLines)==0)
                continue;
            if($filterGroup->JoinType=='and')
            {
                if($andFilters!='')
                    $andFilters=$andFilters.' and ';
                $andFilters.=$filterGroup->CreateGroupString();
            }
        }
        if($andFilters!='')
            $andFilters=' and ('.$andFilters.')';

        foreach($this->Filters as $filterGroup)
        {
            if($filterGroup->JoinType=='or')
            {
                if($orFilters!='')
                    $orFilters=$orFilters.' or ';
                $orFilters.=$filterGroup->CreateGroupString();
            }
        }
        if($orFilters!='')
            $orFilters=' and ('.$orFilters.')';


        $filters=$andFilters.$orFilters;

        if($filters!='')
        {
            if($this->NegateFilter)
                $filters=' and not(1=1 '.$filters.')';

            $filters=' where 1=1'.$filters;
        }

        return $filters;

    }

    private function CreateQueryString($isCountString=false)
    {
        if($isCountString)
            $query=' select count(*) ';
        else
            $query=' select distinct '.\implode(', ',$this->Columns);
        $query.=' from '.$this->RootTable.' '.$this->RootID;

        foreach($this->Dependencies as $dependency)
        {
            $query.=$dependency->CreateJoin();
        }

        return $query;

    }


    /**
     * @param $dependency Dependency
     */
    public function AddDependency($dependency)
    {
        if(ArrayUtils::Some($this->Dependencies,function ($item)use($dependency){
            return $dependency->Id==$item->Id;
        }))
            return;

        $this->Dependencies[]=$dependency;
    }

    private function GenerateSorts()
    {
        $sortText='';
        foreach($this->SortItems as $currentSort)
        {
            $sortId=$currentSort->FieldId.'_'.$currentSort->PathId;
            $dependency=new Dependency($this->Loader->RECORDS_DETAIL,$sortId);
            $this->AddDependency($dependency);
            $dependency->AddComparison(new ColumnComparison('ROOT','entry_id','Equal',$sortId,'entry_id'));
            $dependency->Comparisons[]=new FixedValueComparison($sortId,'field_id','Equal',$currentSort->FieldId);


            if($currentSort->PathId==''||$currentSort->PathId=='Value')
                $dependency->Comparisons[]=new FixedValueComparison($sortId,'subtype','Equal','');
            else
                $dependency->Comparisons[]=new FixedValueComparison($sortId,'subtype','Equal',$currentSort->PathId);


            $column=new QueryElement();
            $column->AddColumn2($sortId,'value',$sortId);
            $this->AddQueryElement($column);

            if($sortText!='')
                $sortText.=",";

            $sortText.=$sortId.' ' .$currentSort->Orientation;
        }
        if($sortText!='')
            $sortText=' order by '.$sortText.' ';
        return $sortText;
    }


}