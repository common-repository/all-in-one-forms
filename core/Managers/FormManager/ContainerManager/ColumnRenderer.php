<?php

namespace rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager;

use rednaoeasycalculationforms\core\Managers\FormManager\FBColumn;
use rednaoeasycalculationforms\core\Managers\FormManager\FBRow;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Renderers\Core\RendererBase;

class ColumnRenderer extends RendererBase
{
    /** @var RowRenderer */
    public $Row;
    /** @var FBColumn */
    public $ColumnOptions;
    public function __construct($loader,$row,$column)
    {
        parent::__construct($loader);
        $this->Row=$row;
        $this->ColumnOptions=$column;
    }

    public function GetColSpan(){
        if(array_search($this,$this->Row->Columns)==count($this->Row->Columns)-1)
        {
            return 1+$this->Row->ContainerManagerRenderer->GetMaximumColumnsPerRow()-count($this->Row->Columns);
        }else
            return 1;
    }

    public function GetValue(){
        if($this->Row->ContainerManagerRenderer->ContainerManager->GetRootFormBuilder()->IsTest)
            return "[".$this->ColumnOptions->Field->GetLabel().']';
        return $this->ColumnOptions->Field->GetHtml($this->Row->ContainerManagerRenderer->Context);
    }

    public function GetLabel(){
        return $this->ColumnOptions->Field->GetLabel();
    }
    protected function GetTemplateName()
    {
        return 'core/Managers/FormManager/ContainerManager/ColumnRenderer.twig';
    }

    protected function PrepareContent()
    {
        // TODO: Implement PrepareContent() method.
    }


}