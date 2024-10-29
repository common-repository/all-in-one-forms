<?php

namespace rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Renderers\Core\RendererBase;

class RowRenderer extends RendererBase
{

    /** @var ColumnRenderer[] */
    public $Columns=[];

    /** @var ContainerManagerRenderer */
    public $ContainerManagerRenderer;
    public function __construct($loader,$containerManagerRenderer)
    {
        parent::__construct($loader);
        $this->ContainerManagerRenderer=$containerManagerRenderer;
    }


    protected function GetTemplateName()
    {
        return 'core/Managers/FormManager/ContainerManager/RowRenderer.twig';
    }

    protected function PrepareContent()
    {
        // TODO: Implement PrepareContent() method.
    }
}