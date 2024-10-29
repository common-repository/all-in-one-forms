<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Templates\FieldSummaryTemplate\SameAsFormTemplate;


use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerManager;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;

class SameAsFormTemplate extends HTMLParserBase
{
    /** @var ContainerManager */
    public $ContainerManager;
    /** @var SameAsFormRow */
    public $Rows=[];
    public function __construct($twig, $formBuilder, $parent, $data,$containerManager)
    {
        parent::__construct($twig, $formBuilder, $parent, $data);
        $this->ContainerManager=$containerManager;
    }


    /** @var FBFieldBase[] */
    public $Fields=[];
    public function ParseContent()
    {
        return $this;
    }

    public function Render()
    {
        return $this->ContainerManager->GetHtml($this->GetDocument()->Context);
    }


    protected function GetTemplateName()
    {
        return 'core/Managers/HTMLGenerator/HTMLParsers/Templates/FieldSummaryTemplate/OneFieldPerRowTemplate.twig';
    }
}