<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


class FBTotalField extends FBFieldBase
{
    protected function InternalGetHtml($document, $formatter = null)
    {
        return parent::InternalGetHtml($document, $formatter);
    }


    protected function CanSanitize()
    {
        return true;
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        $sanitizer->AddStringSanitizer('Value');
        $sanitizer->AddNumericSanitizer('Amount');
    }

    public function GetLineItems()
    {
        $item= parent::GetLineItems()[0];
        $item->Value=$this->GetEntryValue('Value','');
        $item->NumericValue=$this->GetEntryValue('Amount',0);
        return [$item];
    }


}