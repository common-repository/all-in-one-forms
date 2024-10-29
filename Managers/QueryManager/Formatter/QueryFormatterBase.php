<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter;


use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Integration\DateIntegration;
use rednaoeasycalculationforms\core\Integration\UserIntegration;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\Managers\QueryManager\QueryManager;
use stdClass;

abstract class QueryFormatterBase
{
    /** @var QueryManager */
    public $QueryManager;
    public $DateIntegration;
    public $UserIntegration;
    /** @var FormBuilder */
    private $_formBuilder;
    public function __construct($queryManager)
    {
        $this->QueryManager=$queryManager;
        $this->DateIntegration=new DateIntegration($this->QueryManager->Loader);
        $this->UserIntegration=new UserIntegration($this->QueryManager->Loader);
    }



    public abstract function FormatRow($row);

    public function CanAddMultipleRows()
    {
        return false;
    }
    public function GetFormBuilder(){
        if($this->_formBuilder==null)
        {
            $repository = new FormRepository($this->QueryManager->Loader);
            $form=$repository->GetForm($this->QueryManager->FormId)->Options;

            $this->_formBuilder = new FormBuilder($this->QueryManager->Loader, $form, null);
            $this->_formBuilder->Initialize();
        }
        return $this->_formBuilder;

    }

    public function PostProcess($itemsToReturn)
    {
        return $itemsToReturn;
    }


}