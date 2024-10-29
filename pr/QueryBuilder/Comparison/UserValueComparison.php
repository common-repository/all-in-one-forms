<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\ComparisonFormatterBase;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\StringComparisonFormatter;
use rednaoeasycalculationforms\pr\QueryBuilder\Filters\FilterLineBase;
use rednaoeasycalculationforms\pr\QueryBuilder\QueryElement\Dependency;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class UserValueComparison extends ComparisonBase
{
    public $Table;
    public $Column;
    public $Value;
    /** @var ComparisonFormatterBase */
    public $ComparisonFormatter;
    /** @var FilterLineBase */
    public $FilterLine;

    /**
     * FixedValueComparison constructor.
     * @param $FilterLine
     * @param $Table
     * @param $Column
     * @param $Comparison
     * @param $Value
     * @param null $ComparisonFormatter ComparisonFormatterBase
     */
    public function __construct($FilterLine,$Table, $Column, $Comparison, $Value,$ComparisonFormatter=null)
    {
        $this->FilterLine=$FilterLine;
        $this->Table = $Table;
        $this->Column = $Column;
        $this->Comparison = $Comparison;
        $this->Value = $Value;
        $this->ComparisonFormatter=$ComparisonFormatter;

        if($this->ComparisonFormatter==null)
            $this->ComparisonFormatter=new StringComparisonFormatter();
    }


    public function CreateComparison()
    {
        return $this->CreateComparisonString($this->Table.'.'.$this->Column,$this->ComparisonFormatter->Format($this->Value));
    }

    public function CreateComparisonString($leftSide, $rightSide)
    {


        switch ($this->Comparison)
        {
            case 'ViewingPage':
                $userId=get_current_user_id();
                if($userId==0)
                    $userId=-1;
                return (new ListFixedValueComparison('ROOT','user_id','','user_id','Contains',[$userId]))->CreateComparison();
            case 'Is':
                return (new ListFixedValueComparison('ROOT','user_id','','user_id','Contains',$this->Value))->CreateComparison();
            case 'IsNot':
                return (new ListFixedValueComparison('ROOT','user_id','','user_id','NotContains',$this->Value))->CreateComparison();
            case 'IsGuest':
                return (new ListFixedValueComparison('ROOT','user_id','','user_id','Contains',[0]))->CreateComparison();
            case 'IsNotGuest':
                return (new ListFixedValueComparison('ROOT','user_id','','user_id','NotContains',[0]))->CreateComparison();
            case 'IsPartOfRole':
            case 'IsNotPartOfRole':
                global $wpdb;

                $userMetaDependency=new Dependency($wpdb->usermeta,'usermeta',$this->Comparison=='IsNotPartOfRole');
                $userMetaDependency->Comparisons[]=new ColumnComparison('ROOT','user_id','Equal','usermeta','user_id');
                $userMetaDependency->Comparisons[]=new FixedValueComparison('usermeta','meta_key','Equal',$wpdb->prefix.'capabilities');

                if(!$this->FilterLine->HasDependency($userMetaDependency))
                    $this->FilterLine->Dependencies[]=$userMetaDependency;

                $comparisonType=null;
                $comparison=null;
                $comparison = new OrValueComparison();
                $comparisonType='Contains';

                foreach(Sanitizer::SanitizeArray($this->Value) as $currentRole)
                {
                    $comparison->AddComparison(new FixedValueComparison('usermeta','meta_value',$comparisonType,':"'.Sanitizer::SanitizeString($currentRole).'";'));
                }


                return $comparison->CreateComparison();
            default:
                throw new FriendlyException('Invalid comparison type '.$this->Comparison);


        }
    }


}