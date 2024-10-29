<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


class OrValueComparison extends ComparisonBase
{
    /** @var ComparisonBase[] */
    private $Comparisons;

    public function __construct()
    {
        $this->Comparisons=[];
    }


    public function CreateComparison()
    {
        $text='';
        foreach($this->Comparisons as $currentComparison)
        {
            if($text!='')
                $text.= ' or ';
            $text.=$currentComparison->CreateComparison();
        }

        return '('.$text.')';

    }

    /**
     * @param $comparison ComparisonBase
     */
    public function AddComparison($comparison)
    {
        $this->Comparisons[]=$comparison;
    }
}