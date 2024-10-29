<?php


namespace rednaoeasycalculationforms\pages;


use rednaoeasycalculationforms\ajax\FormListAjax;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\OptionsManager;
use rednaoeasycalculationforms\core\db\FieldTemplateRepository;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\FieldsDictionary\FieldsDictionary;
use rednaoeasycalculationforms\core\Integration\FilterManager;
use rednaoeasycalculationforms\core\Integration\IntegrationURL;
use rednaoeasycalculationforms\core\Integration\Media\MediaIntegration;
use rednaoeasycalculationforms\core\Integration\PageIntegration;
use rednaoeasycalculationforms\core\Integration\UserIntegration;
use rednaoeasycalculationforms\core\LibraryManager;
use rednaoeasycalculationforms\core\PageBase;
use rednaoeasycalculationforms\Managers\FontManager\FontManager;

class FormList extends PageBase
{

    public function Render()
    {
        $this->Loader->CheckIfPDFAdmin();

        $mediaIntegration = new MediaIntegration($this->Loader);
        $mediaIntegration->EnqueueMedia();
        $libraryManager = new LibraryManager($this->Loader);

        $libraryManager->AddLit();
        $libraryManager->AddCore();
        $libraryManager->AddCoreUI();
        $libraryManager->AddWPTable();
        $libraryManager->AddDialog();
        $libraryManager->AddPreMadeDialog();
        $libraryManager->AddTabs();
        $libraryManager->AddSpinner();
        $libraryManager->AddAlertDialog();

        $previewNonce = \wp_create_nonce('rednaoeasycalculationforms_FormList');
        if (!isset($_GET['formid'])) {
            $libraryManager->AddInputs();
            $libraryManager->AddTranslator(array('FormList'));

            $this->Loader->AddScript('Bootstrap', 'js/dist/RNMainFormList_bundle.js', $libraryManager->dependencies);
            $this->Loader->AddStyle('Bootstrap', 'js/dist/RNMainFormList_bundle.css');
            $formList = new FormListAjax($this->Loader, true);
            $manager = new DBManager();
            $count = $manager->GetVar('select count(*) from ' . $this->Loader->FORM_LIST_TABLE);
            if ($count == null)
                $count = 0;

            $this->Loader->LocalizeScript('RednaoFormListVar', 'Bootstrap', 'FormList', array(
                'Records' => $formList->ListForm('form_name', 30, 0, 'asc', '')['Result'],
                'IsPr' => $this->Loader->IsPR(),
                'Count' => $count,
                'Templates' => \json_decode(\file_get_contents($this->Loader->DIR . 'Templates/Locals/LocalTemplateList.json')),
                'RemoteTemplates' => \json_decode(\file_get_contents($this->Loader->DIR . 'Templates/list.json')),
                'URL' => $this->Loader->URL,
                'TemplatePreviewURL' => 'https://allinoneforms.rednao.com/form-demo-2/?templateId=',
                'ajaxurl' => IntegrationURL::AjaxURL(),
                'PreviewURL' => IntegrationURL::PreviewURL() . '&_nonce=' . $previewNonce,
                'PageURL' => IntegrationURL::PageURL('rednao_calculation_form')));
            echo '<div id="App"></div>';

        } else {

            $libraryManager->AddFormBuilderDesigner();
            $formId = intval($_GET['formid']);
            $builderOptions=null;
            if ($formId != 0) {
                $formRepository = new FormRepository($this->Loader);
                $builderOptions = $formRepository->GetForm($formId, true)->Options;
            }


            $additionalFields = array();
            $additionalFields = FilterManager::ApplyFilters('rednao-calculated-fields-get-additional-fields', $additionalFields);
            $dependencies = array();
         /*   foreach ($additionalFields as $field) {
                $dependencies[] = '@' . $field;
                $this->Loader->AddScript($field, 'js/dist/' . $field . '_bundle.js', array('@form-builder'));
            }

            if ($this->Loader->IsPR()) {
                $fieldsWidthStyle = ['FBDatePicker', 'FBSingleLabel', 'FBDateRange', 'FBFile', 'FBGroupPanel', 'FBFloatPanel', 'FBImagePicker', 'FBList', 'FBRepeater', 'FBSlider', 'FBButtonSelection', 'FBSignature', 'FBColorSwatcher', 'FBTermOfService', 'FBRadio', 'FBCheckBox', 'FBDivider', 'FBSurvey'];
                foreach ($fieldsWidthStyle as $currentDynamicField) {
                    $this->Loader->AddStyle($currentDynamicField, 'js/dist/' . $currentDynamicField . '_bundle.css');
                }
            }


            $dependencies = FilterManager::ApplyFilters('rednao-calculated-fields-load-designer', $dependencies);

            if ($this->Loader->IsPR()) {
                $this->Loader->AddScript('FormBuilderPr', 'js/dist/FormBuilderPr_bundle.js', array('@form-builder'));
            }


            $this->Loader->AddScript('products-builder', 'js/dist/ProductFieldBuilder_bundle.js', array('@form-builder'));
            $this->Loader->AddStyle('products-builder', 'js/dist/ProductFieldBuilder_bundle.css');

            if ($this->Loader->IsPR()) {
                $this->Loader->AddScript('multiplesteps', 'js/dist/MultipleSteps_bundle.js', array('@form-builder'));
                $this->Loader->AddScript('multiplestepsdesigner', 'js/dist/MultipleStepsDesigner_bundle.js', array('@products-builder'));

                $dependencies[] = '@multiplesteps';
                $dependencies[] = '@multiplestepsdesigner';
            }


            $dependencies = \apply_filters('easycalculationforms_loading_form_designer', $dependencies);
*/

            $dependencies = \apply_filters('allinoneforms_loading_form_designer', $dependencies);
            $this->Loader->AddStyle('material','styles/themes/Material.css');
            $dependencies= \apply_filters('allinoneforms_loading_all_fields', $dependencies);
            $settingsRepository = new SettingsRepository($this->Loader);

            $nextNumber = 1;
            if ($formId != 0) {
                $options = new OptionsManager();
                $formSequence = $this->Loader->Prefix . '_sequence_' . $formId;
                $nextNumber = $options->GetOption($formSequence, 0) + 1;
            }

            $userIntegration = new UserIntegration($this->Loader);
            $pageIntegration = new PageIntegration($this->Loader);

            $this->Loader->AddScript('products-builder-runnable', 'js/dist/RNMainRunnableFormBuilder_bundle.js', array_merge($dependencies, $libraryManager->dependencies));
            $fieldTemplate=new FieldTemplateRepository($this->Loader);

            $fontManager=new FontManager();
            $fonts=$fontManager->GetFonts();
            $fontFaces='';


            foreach($fonts as $key=>$value)
            {
                $fontFaces.=$fontManager->GetFontFace($key);
            }

            $this->Loader->LocalizeScript('rednaoFormDesigner', 'FormBuilderDesigner', 'FormList', array(
                'URL' => $this->Loader->URL,
                'FormListURL' => IntegrationURL::PageURL('rednao_calculation_form'),
                'IsDesign' => true,
                'BuilderOptions'=>$builderOptions,
                'Pages' => $pageIntegration->GetPageList(),
                'IsPr' => $this->Loader->IsPR(),
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
                'Roles' => $userIntegration->GetRoles(),
                'Fonts'=>$fonts,
                'DontShowAgain'=>get_option($this->Loader->Prefix.'_dont_show_again'),
                'FieldTemplates'=>$fieldTemplate->GetList(),
                'AdditionalOptions'=>[
                    'FormDesignNonce'=>wp_create_nonce('rndesign_form_'.$formId),
                    "URL"=>$this->Loader->URL,
                    "GoogleMapsApiKey"=>$settingsRepository->GetGoogleMapsApiKey(),
                    'TCal'=>$libraryManager->GetCalendarTranslations()
                ]
            ));

            echo '
<style>
    '.$fontFaces.'
</style>

<div id="App" style=" position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    background-color: white;">
        <style>                                  
            .lds-hourglass {
              display: inline-block;
              position: relative;
              width: 80px;
              height: 80px;
            }
            .lds-hourglass:after {
              content: " ";
              display: block;
              border-radius: 50%;
              width: 0;
              height: 0;
              margin: 8px;
              box-sizing: border-box;
              border: 32px solid black;
              border-color: black transparent black transparent;
              animation: lds-hourglass 1.2s infinite;
            }
            @keyframes lds-hourglass {
              0% {
                transform: rotate(0);
                animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
              }
              50% {
                transform: rotate(900deg);
                animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
              }
              100% {
                transform: rotate(1800deg);
              }
            }
 
        </style>
        <div style="width:100%;height:100%;justify-content: center;align-items: center;font-size: 20px;font-weight: bold;display: flex;flex-direction: column;">
            <div class="lds-hourglass"></div>
            <div style="font-size: 30px;margin-top: 10px;">' . __("Loading form builder...") . '</div>
        </div>';

        }
    }
}