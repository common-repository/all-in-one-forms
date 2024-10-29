<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBHTML extends FBFieldBase
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
        $item= parent::GetLineItems()[0];
        $item->Value=Sanitizer::GetValueFromPath($this->Entry,['Value','Text']);
        $item->ExValue1=Sanitizer::GetValueFromPath($this->Entry,['Value','HTML']);
        return [$item];
    }

    public function GetHTMLTemplate($context=null)
    {
        return "core/Managers/FormManager/Fields/FBHTML.twig";
    }


}