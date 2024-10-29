<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBNumberField extends FBTextField
{
    public function GetLineItems()
    {
        $items= parent::GetLineItems();
        $items[0]->NumericValue=Sanitizer::SanitizeNumber($this->Entry->Value);
        return $items;
    }


}