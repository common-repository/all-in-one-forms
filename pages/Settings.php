<?php


namespace rednaoeasycalculationforms\pages;


use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\Integration\IntegrationURL;
use rednaoeasycalculationforms\core\LibraryManager;
use rednaoeasycalculationforms\core\PageBase;
use rednaoeasycalculationforms\pr\Utilities\Activator;

class Settings extends PageBase
{

    public function Render()
    {
        $this->Loader->CheckIfPDFAdmin();
        $settingsRepository=new SettingsRepository($this->Loader);

        $libraryManager = new LibraryManager($this->Loader);
        $libraryManager->AddLit();
        $libraryManager->AddCore();
        $libraryManager->AddCoreUI();
        $libraryManager->AddTabs();
        $libraryManager->AddSpinner();
        $libraryManager->AddInputs();
        $libraryManager->AddSwitchContainer();

        $lisense='';
        if($this->Loader->IsPR())
            $lisense=Activator::GetLicense();

        $addOnOptions=[];

        $addOnOptions=apply_filters('allinoneforms_settings_add_on_options',$addOnOptions);
        $dependencies=[];
        $dependencies=apply_filters('allinoneforms_loading_settings_designer',$dependencies);
        $this->Loader->AddRNTranslator('Settings');
        $this->Loader->AddScript('settings','js/dist/RNMainSettings_bundle.js',array_merge($dependencies,$libraryManager->dependencies));
        $this->Loader->AddStyle('settings','js/dist/RNMainSettings_bundle.css');
            $this->Loader->LocalizeScript('RNSettingsVar','settings','Settings',array(
            'Currency'=>$settingsRepository->GetCurrency(),
            'LogOptions'=>$settingsRepository->GetLog(),
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            'GoogleMapsApiKey'=>$settingsRepository->GetGoogleMapsApiKey(),
            'Recaptcha'=>$settingsRepository->GetRecaptchaSettings(),
             "IsPR"=>$this->Loader->IsPR(),
            'BaseUrl'=>get_home_url(),
             'LicenseKey'=>$lisense!=null?$lisense->LicenseKey:'',
             'LicenseURL'=>$lisense!=null?$lisense->URL:'',
            'AddOnOptions'=>$addOnOptions
        ));


        echo "<div id='App'></div>";
    }
}