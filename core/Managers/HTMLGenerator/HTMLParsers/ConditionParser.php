<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use rednaoeasycalculationforms\core\Managers\ConditionManager\ConditionManager;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserWithChildren;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLSimpleContainer;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\ParserUtilities;
use rednaoeasycalculationforms\DTO\ConditionBaseOptionsDTO;

class ConditionParser extends HTMLParserWithChildren
{
    /** @var ConditionBaseOptionsDTO */
    public $Condition;

    public function ParseContent()
    {
        $condition=json_decode($this->GetStringAttributeValue('condition'));
        if($condition==false)
            return null;

        $this->Condition=(new ConditionBaseOptionsDTO())->Merge($condition);
        $condition=new ConditionManager();
        if($condition->ShouldProcess($this->FormBuilder, $this->Condition))
            return parent::ParseContent();
        return null;
    }


    public function Render()
    {
        return $this->RenderChildren();

    }

    protected function GetTemplateName()
    {
        // TODO: Implement GetTemplateName() method.
    }
}