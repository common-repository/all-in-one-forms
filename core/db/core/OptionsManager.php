<?php


namespace rednaoeasycalculationforms\core\db\core;


class OptionsManager
{
    public $Loader;
    public function __construct($loader=null)
    {
        $this->Loader=$loader;
    }

    public function GetOption($optionName,$default=''){
        return \get_option($optionName,$default);
    }

    public function SaveOptions($optionName,$value){
        return \update_option($optionName,$value);
    }
}