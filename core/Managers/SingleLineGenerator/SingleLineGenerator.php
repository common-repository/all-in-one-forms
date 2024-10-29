<?php

namespace rednaoeasycalculationforms\core\Managers\SingleLineGenerator;

use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\Utilities\ObjectSanitizer;

class SingleLineGenerator
{
    /** @var FormBuilder */
    public $FormBuilder;
    public $Options;
    public function __construct($formBuilder)
    {
        $this->FormBuilder=$formBuilder;
        if($formBuilder!=null)
            require_once $this->FormBuilder->Loader->DIR.'vendor/autoload.php';
    }

    public function GetText($content){
        if($content==null)
            return '';

        if(is_string($content))
            return $content;

        $content=ObjectSanitizer::Sanitize($content,["content"=>[(object)[
            "content"=>(object)[
                "type"=>''
            ]
        ]]]);



        $text='';
        foreach($content->content as $currentItem)
        {
            switch ($currentItem->type)
            {
                case 'text':
                    $text.=$currentItem->text;
                    break;
                case 'field':
                    $obj=ObjectSanitizer::Sanitize($currentItem,(object)['attrs'=>(object)["Type"=>'',"Value"=>""]]);
                    if($obj->attrs->Type=='Field')
                    {
                        if($this->FormBuilder==null)
                            break;
                        $field=$this->FormBuilder->GetFieldById($obj->attrs->Value);
                        if($field!=null)
                        {
                            $newText=null;
                            $newText=apply_filters('allinoneforms_format_field',$newText,$this->FormBuilder->Entry,$obj->attrs->Value,$field);
                            if($newText===null)
                                $text .= $field->ToText();
                            else
                                $text.=$newText;
                        }
                    }

                    if($obj->attrs->Type=='fixed')
                    {
                        $text.= apply_filters('allinoneforms_format_fixed_field','',$this->FormBuilder->Entry,$obj->attrs->Value,null);
                    }
                    $text.= apply_filters('allinoneforms_get_single_line_text','',$obj->attrs,$this->FormBuilder);

            }
        }

        return $text;
    }

}