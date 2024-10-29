<?php


namespace rednaoeasycalculationforms\ajax;



use Exception;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;

class SettingsAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'Settings';
    }


    protected function RegisterHooks()
    {
        $this->RegisterPrivate('save_settings','Save');
        $this->RegisterPrivate('delete_log','DeleteLog');
        $this->RegisterPrivate('download_log','DownloadLog');
    }


    public function Save(){
        $currency=$this->GetRequired('Currency');
        $log=$this->GetRequired('LogOptions');
        $googleApiKey=$this->GetRequired('GoogleMapsApiKey');
        $recaptcha=$this->GetRequired('Recaptcha');


        do_action('allinoneforms_save_settings',$this->GetRequired('AddOnOptions'));

        $repository=new SettingsRepository($this->Loader);
        $repository->SaveRecaptchaSettings($recaptcha);
        $repository->SetGoogleMapsApiKey($googleApiKey);
        $repository->SaveCurrency($currency);
        $repository->SaveLog($log);
        $this->SendSuccessMessage(true);

    }

    public function DeleteLog(){
        LogManager::RemoveLog();
        $this->SendSuccessMessage(true);
    }

    public function DownloadLog(){


        if(!\file_exists(LogManager::GetLogFilePath()))
        {
            echo "No log file found";
            die();
        }





        header('Content-Disposition: attachment; filename="log.txt"');
        header("Content-Type: text");
        header("Content-Length: " . filesize(LogManager::GetLogFilePath()));
        echo (file_get_contents(LogManager::GetLogFilePath()));
    }


}