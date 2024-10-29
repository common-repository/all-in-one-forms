<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;

class FBTextArea extends FBTextField
{
    public function InternalToText()
    {
        $text= parent::InternalToText();
        return trim(preg_replace('/\s+/', ' ', $text));
    }


    public function GetHTMLTemplate($context=null)
    {
        return 'core/Managers/FormManager/Fields/FBTextArea.twig';
    }


    public function GetRawText(){
        return $this->GetEntryValue('Value');
    }
}