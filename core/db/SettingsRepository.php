<?php


namespace rednaoeasycalculationforms\core\db;


use Exception;
use rednaoeasycalculationforms\core\db\core\OptionsManager;
use rednaoeasycalculationforms\core\db\core\RepositoryBase;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\DTO\LogOptions;
use stdClass;

class SettingsRepository
{
    /** @var Loader */
    public $Loader;
    /** @var OptionsManager */
    public $OptionsManager;

    public function __construct($loader)
    {
        $this->OptionsManager=new OptionsManager();
        $this->Loader=$loader;
    }



    public function GetCurrency(){
        $currency=$this->OptionsManager->GetOption('RednaoCurrency','');

        if($currency=='')
            return array(
                'Format'=>'%1$s%2$s',
                'Decimals'=>"2",
                'ThousandSeparator'=>",",
                'DecimalSeparator'=>".",
                'Symbol'=>"$"
            );


        return $currency;

    }

    /**
     * @return object|LogOptions
     */
    public function GetLog(){
        $log=$this->OptionsManager->GetOption('RednaoFormLog','');

        if($log=='')
            return (object)array(
                'Enable'=>false,
                "LogType"=>0
            );
        return $log;
    }

    public function SaveLog($log){
        $this->OptionsManager->SaveOptions('RednaoFormLog',$log);
    }

    public function SaveCurrency($currency)
    {
        $this->OptionsManager->SaveOptions('RednaoCurrency',$currency);
    }

    public function GetGoogleMapsApiKey()
    {
        return $this->OptionsManager->GetOption('GoogleApiKey','');
    }



    public function SetGoogleMapsApiKey($apiKey)
    {
        $this->OptionsManager->SaveOptions('GoogleApiKey',$apiKey);
    }


    public function SaveRecaptchaSettings($settings)
    {
        $this->OptionsManager->SaveOptions('RNRecaptchaSettings',$settings);
    }


    /**
     * @return RecaptchaSettings
     */
    public function GetRecaptchaSettings()
    {
        $recaptcha=$this->OptionsManager->GetOption('RNRecaptchaSettings','');
        if($recaptcha=='')
        {
            return (object)array(
                'Type'=>'',
                'SiteKey'=>'',
                'SecretKey'=>'',
                'MinimumScore'=>'.4'
            );
        }

        return $recaptcha;
    }

    public function GetRecaptchaPublicSettings()
    {
        $recaptcha=$this->OptionsManager->GetOption('RNRecaptchaSettings','');
        if($recaptcha=='')
        {
            return (object)array(
                'Type'=>'',
                'SiteKey'=>'');
        }

        unset($recaptcha->SecretKey);

        return $recaptcha;
    }

    /** FormStatus */
    public function GetFormStatusList(){
        return array(
            new FormStatus('pending_payment',__('Pending Payment')),
            new FormStatus('awaiting_approval',__('Awaiting Approval')),
            new FormStatus('failed_payment',__('Failed Payment')),
            new FormStatus('processing',__('Processing')),
            new FormStatus('cancelled',__('Cancelled')),
            new FormStatus('refunded',__('Refunded')),
            new FormStatus('onhold',__('OnHold')),
            new FormStatus('completed',__('Completed'))
        );
    }





}

class FormStatus{
    public $Label;
    public $Type;


    public function __construct($type,$label)
    {
        $this->Label=$label;
        $this->Type=$type;
    }


}

class RecaptchaSettings{
    public $Type;
    public $SiteKey;
    public $SecretKey;
    public $MinimumScore;
}