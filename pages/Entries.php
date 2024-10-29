<?php

namespace rednaoeasycalculationforms\pages;

use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\Integration\FilterManager;
use rednaoeasycalculationforms\core\Integration\IntegrationURL;
use rednaoeasycalculationforms\core\Integration\Media\MediaIntegration;
use rednaoeasycalculationforms\core\Integration\PageIntegration;
use rednaoeasycalculationforms\core\Integration\UserIntegration;
use rednaoeasycalculationforms\core\LibraryManager;
use rednaoeasycalculationforms\core\PageBase;

class Entries extends PageBase
{

    public function Render()
    {
        $this->Loader->CheckIfPDFAdmin();
        $mediaIntegration = new MediaIntegration($this->Loader);
        $mediaIntegration->EnqueueMedia();
        $libraryManager = new LibraryManager($this->Loader);

        $libraryManager->AddAlertDialog();
        $libraryManager->AddFormBuilderDesigner();
        $builderOptions=null;




        $additionalFields = array();
        $additionalFields = FilterManager::ApplyFilters('rednao-calculated-fields-get-additional-fields', $additionalFields);
        $additionalFields = \apply_filters('allinoneforms_loading_form_designer', $additionalFields);
        $additionalFields= \apply_filters('allinoneforms_loading_all_fields', $additionalFields);



        $settingsRepository = new SettingsRepository($this->Loader);

        $nextNumber = 1;


        $userIntegration = new UserIntegration($this->Loader);
        $pageIntegration = new PageIntegration($this->Loader);


        wp_enqueue_script('rednaoallinoneentries_entries',$this->Loader->URL.'js/dist/RNMainEntryLt_bundle.js',array_merge($libraryManager->GetDependencyHooks()),$this->Loader->FILE_VERSION);
        $this->Loader->AddStyle('entries', 'js/dist/RNMainEntryLt_bundle.css');

        $dependencies=['rednaoallinoneentries_entries'];
        $dependencies=apply_filters('allinoneforms_get_entries_dependencies',$dependencies);

        $this->Loader->AddScript('runnableentries', 'js/dist/RNMainRunnableEntriesLt_bundle.js', $dependencies);
        $dbManager=new DBManager();

        $forms= $dbManager->GetResults('select form_id,form_name,element_options,client_form_options,icons from '.$this->Loader->FORM_LIST_TABLE.' order by form_name');

        foreach($forms as $currentForm)
        {
            $currentForm->SubmitNonce=wp_create_nonce('rnsusbmit_form_'.$currentForm->form_id);
        }

        $viewEntry=null;
        if(isset($_GET['entryid']))
        {
            $entry=AllInOneForms()->Entry()->Get($_GET['entryid']);
            if($entry!=null)
            {
                $entry->EditNonce=wp_create_nonce('edit_entry_'.$entry->EntryId);
                $viewEntry=$entry;
            }
        }


        $this->Loader->LocalizeScript('rednaoFormDesigner', 'FormBuilderDesigner', 'Entries', array(
            "FormList"=>$forms,
            "CoreURL"=>AllInOneForms()->GetLoader()->URL,
            'URL' => $this->Loader->URL,
            'FormListURL' => IntegrationURL::PageURL('rednao_calculation_form'),
            'IsDesign' => true,
            'ViewEntry'=>$viewEntry,
            'BuilderOptions'=>$builderOptions,
            'Pages' => $pageIntegration->GetPageList(),
            'IsPr' => true,
            'SettingsURL' => IntegrationURL::PageURL('rednao_calculation_form_settings'),
            'GoogleMapsApiKey' => $settingsRepository->GetGoogleMapsApiKey(),
            'PurchaseURL' => 'http://google.com',
            'ajaxurl' => IntegrationURL::AjaxURL(),
            'PreviewURL' => IntegrationURL::PreviewURL(),
            'Recaptcha' => $settingsRepository->GetRecaptchaPublicSettings(),
            'Currency' => $settingsRepository->GetCurrency(),
            'StatusList' => $settingsRepository->GetFormStatusList(),
            'UserRoles' => array(),
            'NextNumber' => $nextNumber,
            'Roles' => $userIntegration->GetRoles()
        ));


        echo '
            
            <div id="App"></div>';

    }
}