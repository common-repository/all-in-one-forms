<?php


namespace rednaoeasycalculationforms\core\Managers\ConditionManager;


use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\ComparatorFactory;
use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\FixedSource;
use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\MultipleValueComparator;
use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\VariationSource;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\Managers\FormulaManager\FormulaManager;
use rednaoeasycalculationforms\Parser\Core\DataRetriever;
use rednaoeasycalculationforms\Parser\Elements\ParseMain;

class ConditionManager
{
    /**
     * @param $model FormBuilder
     * @param $condition
     * @return bool
     */
    public function ShouldProcess($model,$condition)
    {
        if($condition==null||count($condition->ConditionGroups)==0||$model->IsTest)
            return true;

        if($condition->Formula!=null)
        {
            $parser=new ParseMain($condition->Formula->Compiled,new DataRetriever($model));
            return $parser->Parse();
        }
        foreach($condition->ConditionGroups as $group)
        {
            $groupIsValid=true;
            foreach($group->ConditionLines as $line)
            {
                if(!$groupIsValid)
                    break;

                switch($line->Type)
                {
                    case 'Field':
                    case 'Standard':
                        if(\strpos($line->FieldId,'_')===0)
                        {
                            if($line->FieldId=='_Status')
                            {

                                if($line->Comparison=='ChangedTo'||$line->Comparison=='ChangedFrom')
                                {
                                    if($model->Entry->Status==$model->OriginalStatus)
                                    {
                                        $groupIsValid = false;
                                        break;
                                    }
                                }
                                if($line->Comparison=='ChangedFrom')
                                    $status=new FixedSource(null,array((object)array('Id'=>$model->OriginalStatus)));
                                else
                                    $status=new FixedSource(null,array((object)array('Id'=>$model->Entry->Status)));
                                $comparator=new MultipleValueComparator($model,$status);
                                $groupIsValid = $comparator->Compare($line->Comparison, $line->Value);
                            }



                        }else
                        {
                            $field = $model->ContainerManager->GetFieldById($line->FieldId, false, true);
                            if ($field != null) {

                                $comparator = ComparatorFactory::GetComparator($model, $field);
                                $groupIsValid = $comparator->Compare($line->Comparison, $line->Value);
                            }
                        }

                        break;
                }
            }

            if($groupIsValid)
                return true;

        }

        return false;

    }
}