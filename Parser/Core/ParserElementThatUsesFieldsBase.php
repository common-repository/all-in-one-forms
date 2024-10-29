<?php


namespace rednaoeasycalculationforms\Parser\Core;


use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;

abstract class ParserElementThatUsesFieldsBase extends ParserElementBase
{
    /**
     * @param $field FBFieldBase
     * @return int|null
     */
    public function GetPriceFromField($field)
    {
        if($field instanceof FBFieldBase&&$this->GetMain()->Owner==$field)
            return $field->GetPriceWithoutFormula();
        return $field->GetPrice();
    }


}