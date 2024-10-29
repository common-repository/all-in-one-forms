<?php

namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


class FBColorPicker extends FBFieldWithPrice
{
    public function GetHTMLTemplate($context = null)
    {
        return 'core/Managers/FormManager/Fields/FBColorPicker.twig';
    }

    protected function CanSanitize()
    {
        return true;
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        $sanitizer->AddStringSanitizer('Value');
    }

}