<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;

class FBSwitch extends FBFieldWithPrice
{
    protected function InternalGetHtml($document, $formatter = null)
    {
        $span = new HtmlTagWrapper($document, $document->createElement('span'));

        if ($this->Entry->Value)
        {
            $span->SetHtml('&#10004; ');
        }else
            $span->SetHtml('&#10006; ');

        return $span;
    }

    public function InternalToText()
    {
        if ($this->Entry->Value)
        {
            return __('Yes');
        }else
            return __('No');
    }


}