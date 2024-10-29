<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLSimpleContainer;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\ParserUtilities;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class FieldParser extends HTMLParserBase
{
    public $Id;
    public $Options;
    /** @var FBFieldBase */
    public $Field;
    public function ParseContent()
    {
        $this->Id=$this->GetAttributeValue('id');
        $this->Options=$this->GetAttributeValue('options');

        $this->Field=$this->FormBuilder->GetFieldById($this->Id);
        if($this->Field==null&&$this->GetIsField())
            return null;

        return ParserUtilities::MaybeApplyMarks($this);
    }

    public function GetIsField(){
        return Sanitizer::GetStringValueFromPath($this->Data,['attrs','type'])=='field';
    }
    public function GetIsFixed(){
        return Sanitizer::GetStringValueFromPath($this->Data,['attrs','type'])=='fixed';
    }
    public function Render()
    {
        if($this->GetIsFixed())
        {
            switch ($this->Id)
            {
                case 'EntryNumber':
                    if($this->FormBuilder->IsTest)
                        return '[Entry Number]';
                    return apply_filters('allinoneforms_format_fixed_field','',$this->FormBuilder->Entry,$this->Id,null);
                case 'SubmissionDate':
                    if($this->FormBuilder->IsTest)
                        return '[Submission Date]';
                    return apply_filters('allinoneforms_format_fixed_field','',$this->FormBuilder->Entry,$this->Id,null);
                case 'CurrentDate':
                    if($this->FormBuilder->IsTest)
                        return '[Current Date]';
                    return apply_filters('allinoneforms_format_fixed_field','',$this->FormBuilder->Entry,$this->Id,null);
                case 'EntryTotal':
                    if($this->FormBuilder->IsTest)
                        return '[Entry Total]';
                    return apply_filters('allinoneforms_format_fixed_field','',$this->FormBuilder->Entry,$this->Id,null);
                case 'OriginURL':
                    if($this->FormBuilder->IsTest)
                        return '[Origin URL]';
                    return apply_filters('allinoneforms_format_fixed_field','',$this->FormBuilder->Entry,$this->Id,null);
                default:
                    if($this->FormBuilder->IsTest)
                        return '['. preg_replace('/(?<!^)([A-Z])/', ' $1', $this->Id).']';

                    return apply_filters('allinoneforms_format_fixed_field','',$this->FormBuilder->Entry,$this->Id,Sanitizer::GetValueFromPath($this->Data,['attrs','options','']));
            }
        }

        if($this->GetIsField()) {
            $field = $this->FormBuilder->GetFieldById($this->Id);
            if ($this->FormBuilder->IsTest) {
                if ($field == null)
                    return "[Unknown Field]";
                else
                    return "[" . $field->GetLabel() . ']';
            }

            if ($field == null || !$field->IsUsed())
                return '';


            return $field->GetHtml($this->GetDocument()->Context);
        }

        $content='';
        return apply_filters('allinoneforms_parse_custom_field',$content,$this);

    }

    protected function GetTemplateName()
    {
        // TODO: Implement GetTemplateName() method.
    }
}