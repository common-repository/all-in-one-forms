<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Templates\FieldSummaryTemplate;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Renderers\Core\RendererBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use Twig\Markup;

class OneFieldPerRowTemplate extends HTMLParserBase
{
    /** @var FBFieldBase|Markup[] */
    public $Fields=[];
    public function ParseContent()
    {
        $showEmptyFields=Sanitizer::GetBooleanValueFromPath($this->Data,['ShowEmptyFields'],false);
        $fields=$this->FormBuilder->GetFields(true,true);
        foreach($fields as $currentField)
        {
            if(isset($currentField->ContainerManager))
            {
                $columns=$currentField->ContainerManager->GetHeaderColumns();
                if(count($columns)>0)
                    $this->Fields=array_merge($this->Fields,$columns);
                continue;
            }
            if(!$currentField->GetStoresInformation())
                continue;
            if($currentField->IsUsed()||$showEmptyFields)
                $this->Fields[]=$currentField;
        }


        return $this;
    }


    public function Render()
    {
        return $this->RenderTemplate($this->GetTemplateName(),$this);
    }


    protected function GetTemplateName()
    {
        return 'core/Managers/HTMLGenerator/HTMLParsers/Templates/FieldSummaryTemplate/OneFieldPerRowTemplate.twig';
    }


    public function RenderTemplate($templateName,$model)
    {
        $markup= new Markup($this->FormBuilder->Loader->GetTwigManager()->Render($templateName,$model,['Context'=>$this->GetDocument()->Context]),"UTF-8");
        return $markup;
    }
}