<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Templates\FieldSummaryTemplate\FieldSummaryTemplate;

class ParseTemplate extends HTMLParserBase
{
    public function ParseContent()
    {
        $options=json_decode($this->GetStringAttributeValue('Options'));
        if($options==false||!isset($options->Id))
            return null;

        switch ($options->Id)
        {
            case 'field_summary':
                return (new FieldSummaryTemplate($this->FormBuilder,$this->Parent,$this->Data))->ParseContent();
        }
        return null;
    }

    public function Render()
    {
        return 'a';
    }

    protected function GetTemplateName()
    {
        // TODO: Implement GetTemplateName() method.
    }
}