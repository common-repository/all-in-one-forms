<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBSimpleText extends FBFieldBase
{
    public function PrepareForSerialization()
    {
        require_once $this->GetForm()->Loader->DIR.'vendor/autoload.php';
        if(Sanitizer::PathExist($this->Entry,['Value','HTML']))
        {
            $html=$this->Entry->Value->HTML;
            $this->Entry->Value->HTML=$this->Loader->GetHTMLSanitizer()->Sanitize($html);

        }
    }


    public function GetValue()
    {
        $text=Sanitizer::GetStringValueFromPath($this->Entry,['Value','Text'],'');
        $html=Sanitizer::GetStringValueFromPath($this->Entry,['Value','HTML']);
        if($text==''&&$html=='')
            return null;
        return [
            'Text'=>$text,
            'HTML'=>$html
        ];
    }

    protected function CanSanitize()
    {
        return true;
    }


    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        $valueManager=$sanitizer->AddObjectSanitizer('Value')->Manager;
        $valueManager->AddStringSanitizer('Text');
        $valueManager->AddStringSanitizer('HTML');
    }

    protected function InternalToText()
    {
        return Sanitizer::GetStringValueFromPath($this->Entry,['Value','Text'],'');
    }

    public function ToHTML(){
        if($this->GetRootForm()->IsTest)
            return '[Test Value]';
        if(!$this->IsUsed())
            return '';
        return Sanitizer::GetStringValueFromPath($this->Entry,['Value','HTML'],'');
    }


    public function GetLineItems()
    {
        if(!$this->IsUsed())
            return [];
        $item= parent::GetLineItems()[0];

        $item->Value=Sanitizer::GetStringValueFromPath($this->Entry,['Value','Text'],'');
        $item->ExValue1=Sanitizer::GetStringValueFromPath($this->Entry,['Value','HTML']);

        return [$item];
    }

    public function GetHTMLTemplate($context=null)
    {
        return "core/Managers/FormManager/Fields/FBSimpleText.twig";
    }


}