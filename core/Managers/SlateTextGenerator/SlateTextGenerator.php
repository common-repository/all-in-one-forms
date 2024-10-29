<?php


namespace rednaoeasycalculationforms\core\Managers\SlateTextGenerator;


use rednaoeasycalculationforms\core\Integration\DateIntegration;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;

class SlateTextGenerator
{
    /**
     * @var FormBuilder
     */
    public $Model;

    /**
     * SlateTextGenerator constructor.
     * @param $model FormBuilder
     */
    public function __construct($model)
    {
        $this->Model=$model;
    }

    public function GetText($content)
    {
        if(!isset($content->document)||!isset($content->document->nodes))
            return '';

        $text='';

        foreach($content->document->nodes as $paragraph)
        {
            if($paragraph->type!='paragraph')
                continue;
            foreach ($paragraph->nodes as $node)
            {
                switch ($node->object)
                {
                    case 'text':
                        $text .= $this->GetValueFromTextNode($node);
                        break;
                    case 'inline':
                        $text .= $this->GetValueFromFieldNode($node);
                        break;
                }
            }
        }

        return $text;

    }

    private function GetValueFromTextNode($node)
    {
        if(!isset($node->leaves))
            return '';

        $text='';
        foreach($node->leaves as $leaf)
        {
            $text.=$leaf->text;
        }

        return $text;
    }

    private function GetValueFromFieldNode($node)
    {
        if($this->Model->IsTest)
            return '[test_data]';
        $fieldData=$node->data;
        switch ($fieldData->SubType)
        {
            case 'field':
                $field=$this->Model->ContainerManager->GetFieldById($fieldData->FieldId,true,true);
                if($field==null)
                    return '';
                return $field->ToText();
                break;
            case 'fixed':
                switch($fieldData->FieldId)
                {
                    case 'submission_date':
                        $dateIntegration=new DateIntegration($this->Model->Loader);
                        return $dateIntegration->GetTimezonedDateFromUTCDate(date('c',$this->Model->Entry->UnixDate));
                    case 'submission_number':
                        return $this->Model->Entry->FormattedSequence;
                    case 'submission_total':
                        return $this->Model->Entry->Total;

                }


        }

    }


}