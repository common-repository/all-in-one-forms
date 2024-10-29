<?php


use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;

class RNECForms
{
    /**
     * @return Loader
     */
    public static function GetLoader(){
        return apply_filters('rednao-easy-calculation-forms-get-loader',null);
    }

    /**
     * @return FormBuilder[]
     */
    public static function GetForms(){
        $loader=self::GetLoader();
        $formRepository=new FormRepository($loader);
        return $formRepository->GetForms();

    }



}