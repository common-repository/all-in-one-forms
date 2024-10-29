<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;

class FBTermOfService extends FBFieldBase
{
    protected function InternalGetHtml($document, $formatter = null)
    {
        $container=new HtmlTagWrapper($document,$document->createElement('div'));
        $text=$this->GetOptionValue('Text','');
        $text=\str_replace('$$','',$text);

        $check=$container->CreateAndAppendChild('label');
        $check->SetHtml('&#10004; ');
        $label=$container->CreateAndAppendChild('label');
        $label->SetText($text);;

        return $container;
    }

    public function InternalToText()
    {
        $text=$this->GetOptionValue('Text','');
        $text=\str_replace('$$','',$text);
        if($this->GetValue())
            $text=__('Yes, ').$text;
        else
            $text=__('No, ').$text;

        return $text;
    }


}