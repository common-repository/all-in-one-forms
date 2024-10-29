<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBButtonContainer extends FBTextField
{
    public function GetLineItems()
    {
        $items= parent::GetLineItems();
        $items[0]->NumericValue=Sanitizer::SanitizeNumber($this->Entry->Value);
        return $items;
    }

    public function Validate()
    {
        parent::Validate();
        if($this->Entry==null)
            return true;


        $value=Sanitizer::GetStringValueFromPath($this->Entry,['Value'],'');
        if(!is_numeric($value))
            $this->AddError('The value is not a number');



        if($this->Options->MaxValue!=='')
        {
            if($value>$this->Options->MaxValue) {
                $this->AddError('The value is greater than the maximum allowed');
                return false;
            }
        }

        if($this->Options->MinValue!=='')
        {
            if($value<$this->Options->MinValue) {
                $this->AddError('The value is less than the minimum allowed');
                return false;
            }
        }

        return true;



    }


}