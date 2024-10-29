<?php

namespace rednaoeasycalculationforms\pr\Parser\Elements\Operations\Arithmetical;



use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\pr\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementBase;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementThatUsesFieldsBase;

class ParseArithmetical extends ParserElementThatUsesFieldsBase{

    /** @var ParserElementBase */
    public $Left;

    /** @var ParserElementBase */
    public $Right;


    function __construct($Parent,$Data) {
        parent::__construct($Parent,$Data);
        $this->Left=ParseFactory::GetParseElement($this,$this->Data->Left);
        $this->Right=ParseFactory::GetParseElement($this,$this->Data->Right);
    }

    function Parse() {
        switch ($this->Data->type) {
            case 'MUL':
                return $this->GetScalarOrPrice($this->Left->Parse())*$this->GetScalarOrPrice($this->Right->Parse());
            case 'ADD':
                $left=$this->ToScalar($this->Left->Parse());
                $right=$this->ToScalar($this->Right->Parse());

                if($left instanceof FBFieldBase)
                {
                    if(\is_string($right))
                        $left=$left->ToText();
                    else
                        $left=$this->GetScalarOrPrice($left);
                }

                if($right instanceof FBFieldBase)
                {
                    if(\is_string($left))
                        $right=$right->ToText();
                    else
                        $right=$this->GetScalarOrPrice($right);
                }

                if(\is_string($left)||\is_string($right))
                    return $left.$right;

                return $left+$right;
            case 'SUB':
                return $this->GetScalarOrPrice($this->ToScalar($this->Left->Parse()))-$this->GetScalarOrPrice($this->ToScalar($this->Right->Parse()));
            case 'DIV':
                if($this->GetScalarOrPrice($this->ToScalar($this->Right->Parse()))==0)
                    return 0;
                return $this->GetScalarOrPrice($this->ToScalar($this->Left->Parse()))/$this->GetScalarOrPrice($this->ToScalar($this->Right->Parse()));

        }
    }

    function ToScalar($parse)
    {
        if(\is_array($parse))
        {
            return \array_reduce($parse,function ($previous,$next){return $previous+$next;},0);
        }

        return $parse;
    }

    function GetScalarOrPrice($data)
    {
        if($data instanceof FBFieldBase)
            return $this->GetPriceFromField($data);

        return $data;

    }

}