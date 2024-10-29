<?php

namespace rednaoeasycalculationforms\pr\Parser\Elements\Operations\Logical;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\pr\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementBase;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementThatUsesFieldsBase;

class ParseComparator extends ParserElementThatUsesFieldsBase {

    /** @var ParserElementBase */
    public $Left;
    /** @var ParserElementBase */
    public $Right;

    function __construct($Parent,$Data) {
        parent::__construct($Parent,$Data);
        $this->Left=ParseFactory::GetParseElement($this,$Data->Left);
        $this->Right=ParseFactory::GetParseElement($this,$Data->Right);
    }

    function Parse() {
        $operator=$this->Data->operator;

        if($this->Right==null)
            return $this->Left->Parse()==true;

        $originalLeft=$this->Left->Parse();
        $originalRight=$this->Right->Parse();

        $leftValue=$this->Left->Parse();
        $rightValue=$this->Right->Parse();

        if($leftValue instanceof FBFieldBase)
        {
            if(is_string($rightValue))
                $leftValue=$leftValue->ToText();
            else
                $leftValue=$this->GetPriceFromField($leftValue);
        }

        if($rightValue instanceof FBFieldBase)
        {
            if(\is_string($leftValue))
                $rightValue=$this->GetPriceFromField($rightValue);
            else
                $rightValue=$rightValue->GetPrice();
        }


        switch ($operator) {
            case '==':
                return $leftValue==$rightValue;
            case '!=':
                return $leftValue!=$rightValue;
            case '>':
                return $leftValue>$rightValue;
            case '>=':
                return $leftValue>=$rightValue;
            case '<':
                return $leftValue<=$rightValue;
            case '<=':
                return $leftValue<=$rightValue;
            case 'contains':
            case 'not contains':
                $haystack=$leftValue;
                $needle=$rightValue;

            if(!\is_array($needle))
                $needle=[$needle];

            if(!\is_array($haystack))
                $haystack=[$haystack];

                for($i=0;$i<count($haystack);$i++)
                {
                    if($haystack[$i] instanceof FBFieldBase)
                        $haystack[$i]=$this->GetPriceFromField($haystack[$i]);
                }

                for($i=0;$i<count($needle);$i++)
                {
                    if($needle[$i] instanceof FBFieldBase)
                        $needle[$i]=$this->GetPriceFromField($needle[$i]);
                }



                if($operator=='contains') {
                    foreach ($needle as $currentNeedle) {
                        if (\array_reduce($haystack,function ($carry,$item)use($currentNeedle) {if($carry)return true; return $item==$currentNeedle;}))
                            return true;
                    }
                    return false;

                }

                if($operator=='not contains') {
                    foreach ($needle as $currentNeedle) {
                        if (\array_reduce($haystack,function ($carry,$item)use($currentNeedle) {if($carry)return true; return $item==$currentNeedle;}))
                            return false;
                    }
                    return true;

                }


        }

    }

}