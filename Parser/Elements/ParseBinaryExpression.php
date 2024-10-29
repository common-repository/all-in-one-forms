<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParseUtilities;

class ParseBinaryExpression extends ParseBase
{
    /** @var ParseBase */
    public $Left;
    /** @var ParseBase */
    public $Right;
    public $Subtype;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Left = ParseFactory::GetParseElement($options->L, $this);
        $this->Right = ParseFactory::GetParseElement($options->R, $this);
        $this->Subtype = $options->St;
    }


    public function Parse($type = null)
    {
        $left=$this->Left->Parse();
        $right=$this->Right->Parse();

        switch ($this->Subtype)
        {
            case 'Or':
                return $left==true || $right==true;
            case 'And':
                return $left==true && $right==true;
            case 'Add':
                if($left instanceof FBFieldBase) {
                    if (is_string($right))
                        $left = $left->ToText();
                    else
                        $left = $left->ToNumber();

                }else{
                    if($left==''&&!is_numeric($left))
                        $left=strval($left);
                    else
                        $left=floatval($left);
                }

                if($right instanceof FBFieldBase) {
                    if (is_string($left))
                        $right = $right->ToText();
                    else
                        $right = $right->ToNumber();


                }else{
                    if($right==''||!is_numeric($right))
                        $right=strval($right);
                    else
                        $right=floatval($right);
                }

                if(ParseUtilities::IsNumeric($left)&&ParseUtilities::IsNumeric($right))
                    return $left+$right;
                else
                    return $left.$right;

            case 'Sub':
                return $this->ToNumber($left)-$this->ToNumber($right);
            case 'Mult':
                return $this->ToNumber($left)*$this->ToNumber($right);
            case 'Div':
                $right=$this->ToNumber($right);
                if($right==0)
                    return 0;
                return $this->ToNumber($left)/$right;
            case 'Eq':
                return $this->ToScalar($left)==$this->ToScalar($right);
            case 'NEq':
                return $this->ToScalar($left)!=$this->ToScalar($right);
            case 'Gt':
                return $this->ToScalar($left)>$this->ToScalar($right);
            case 'Gte':
                return $this->ToScalar($left)>=$this->ToScalar($right);
            case 'Lt':
                return $this->ToScalar($left)<$this->ToScalar($right);
            case 'Lte':
                return $this->ToScalar($left)<=$this->ToScalar($right);

        }
    }

    public function ToScalar($parse)
    {
        if(is_array($parse))
        {
            $number=0;
            foreach ($parse as $item)
            {
                $number+=$this->ToScalar($item);
            }
        }

        if($parse instanceof FBFieldBase)
            return $parse->ToText();

        return $parse;
    }

    private function ToNumber($parse){
        if(is_array($parse))
        {
            $number=0;
            foreach ($parse as $item)
            {
                $number+=$this->ToNumber($item);
            }
            return $number;
        }

        if($parse instanceof FBFieldBase)
            return $parse->ToNumber();

        if(is_object($parse)&&method_exists($parse,'ToNumber'))
            return $parse->ToNumber();

        $parse=floatval($parse);
        if(!is_numeric($parse))
            return 0;
        return $parse;
    }
}