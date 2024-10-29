<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager;


use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FieldFactory;
use rednaoeasycalculationforms\DTO\FBColumnOptionsDTO;
use undefined\DTO\FBColumnOptions;
use undefined\DTO\FBRowOptions;

class FBColumn
{
    /** @var FBColumnOptionsDTO */
    public $Options;
    /** @var FBFieldBase */
    public $Field;
    /** @var FBRow */
    public $Row;
    public $Loader;
    public function __construct($loader,$row,$options,$entry=null)
    {
        $this->Loader=$loader;
        $this->Row=$row;
        $this->Options=$options;
        $this->Field=null;
        if($options!=null)
            $this->AddField(FieldFactory::GetField($loader,$this,$options->Field,$entry));
    }


    /**
     * @param $field FBFieldBase
     */
    public function AddField($field)
    {
        $this->Field=$field;
    }

    public function Initialize(){
        $this->Field->Initialize();
    }

}