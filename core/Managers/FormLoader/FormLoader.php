<?php


namespace rednaoeasycalculationforms\core\Managers\FormLoader;


use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\FieldsDictionary\FieldsDictionary;
use rednaoeasycalculationforms\core\Integration\IntegrationURL;
use rednaoeasycalculationforms\core\Integration\UserIntegration;
use rednaoeasycalculationforms\core\LibraryManager;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\EntrySaver\ConfirmationActions\MessageConfirmationAction;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLGenerator;
use rednaoeasycalculationforms\core\Managers\RestrictionManager\MaximumSubmissionNumberRestriction;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\DTO\BuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\CurrencyOptionsDTO;
use rednaoeasycalculationforms\DTO\FormBuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\NumberOfSubmissionsRestrictionOptionDTO;
use rednaoeasycalculationforms\DTO\ServerOptionsDTO;
use rednaoeasycalculationforms\Managers\FontManager\FontManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use stdClass;


class FormLoader
{
    /** @var Loader */
    private $Loader;
    public $PreviousData=null;
    /** @var FormBuilderOptionsDTO */
    public $VarData=null;
    public $Id;
    public static $Dependencies=array();
    public static $Translations=array();
    /** @var CurrencyOptionsDTO */
    public static $Currency=null;
    public $EditOptions;
    /** @var $LibraryManager */
    public $LibraryManager;
    public $FormId;
    /** @var BuilderOptionsDTO */
    public $Options;
    public $StatusList;
    public $IsPreview=false;
    public function __construct($Loader)
    {
        $this->LibraryManager=new LibraryManager($Loader);
        $this->StatusList=array();
        $this->Loader=$Loader;
        $this->EditOptions=new stdClass();
        $this->EditOptions->AllowEdition=true;

    }


    public function SetAllowEdition($allowEdition)
    {
        $this->EditOptions->AllowEdition=$allowEdition;

    }

    public function SetAllowStatusEdition(){
        $settingsRepository=new SettingsRepository($this->Loader);
        $this->VarData['StatusList']=$settingsRepository->GetFormStatusList();
        $this->EditOptions->AllowStatusEdition=true;
    }

    public function SetEditNonce($editNonce)
    {
        $this->EditOptions->EditNonce=$editNonce;
    }


    public function LoadForm($options)
    {
        require_once $this->Loader->DIR.'core/Managers/FormLoader/ServerDependencyHooks.php';
        $this->Options=$options;
        $this->FormId=$options->Id;
        if(isset($this->Options->FormBuilder->ExtensionsUsed))
        {
            foreach($this->Options->FormBuilder->ExtensionsUsed as $extension)
            {
                self::$Dependencies=\apply_filters('rednao-calculated-field-get-extension-runnable-'.$extension,self::$Dependencies);
            }
        }



        $this->Id=\uniqid();
        $settingsRepository=new SettingsRepository($this->Loader);
        $userIntegration=new UserIntegration($this->Loader);
        $options=array(
            'FormId'=>$this->Options->Id,
            'SubmitNonce'=>wp_create_nonce('rnsusbmit_form_'.$this->Options->Id),
            'FormContainerId'=>$this->Id,
            'Options'=>$this->Options->FormBuilder,
            "URL"=>$this->Loader->URL,
            'Currency'=>$this->GetCurrency(),
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            'PreviousData'=>null,
            'GoogleMapsApiKey'=>$settingsRepository->GetGoogleMapsApiKey(),
            'Recaptcha'=>$settingsRepository->GetRecaptchaPublicSettings(),
            'EditOptions'=>$this->EditOptions,
            'UserRoles'=>$userIntegration->GetCurrentUserRoles(),
            'Extra'=>[],
            'AdditionalStyles'=>''
        );

        if(count($this->Options->ServerOptions->ServerDependencies)>0)
        {
            foreach($this->Options->ServerOptions->ServerDependencies as $currentDependency)
            {
                $options=apply_filters('allinoneforms_get_server_dependency_'.$currentDependency->Type,$options,$currentDependency);
            }
            $options['Attributes']=$this->GetAttributes();

        }else
            $options['Attributes']=[];

        $this->VarData=$options;
        $this->MaybeIncludeUserInfo();
        $this->MaybeAddAdditionalOptions();
    }

    public function GetCurrency(){
        if(self::$Currency==null)
        {
            $settingsRepository=new SettingsRepository($this->Loader);
            self::$Currency = $settingsRepository->GetCurrency();
        }

        return self::$Currency;
    }


    /**
     * @return FormBuilderOptionsDTO
     */
    public function GetOptions(){
        return $this->Options->FormBuilder;
    }

    public function GetClientExtensionById($id)
    {
        $options=$this->GetOptions();
        foreach ($options->ClientOptions->Extensions as $extension)
        {
            if($extension->Id==$id)
                return $extension;
        }
        return null;
    }

    public function GetServerExtensionById($id)
    {
        if(!isset($this->Options->ServerOptions))
            return null;
        foreach ($this->Options->ServerOptions->Extensions as $extension)
        {
            if($extension->Id==$id)
                return $extension;
        }
        return null;
    }


    public function Load(){

        $this->LibraryManager->AddCoreUI();
        $this->LibraryManager->AddFormBuilderCore();
        $this->LibraryManager->AddSpinner();
        if(!$this->IsPreview&& $this->Options->ServerOptions!=null)
        {
            $errorMessage=$this->ValidateRestrictions($this->Options->ServerOptions);
            if($errorMessage!='')
            {
                echo "
                    <div>
                        $errorMessage
                    <div>                      
                    ";
                return false;
            }
        }


        $options=$this->GetOptions();
        if(isset($options->ClientOptions)&&$options->ClientOptions->MultipleSteps!=null)
        {
            $this->Loader->AddScript('MultipleSteps','js/dist/RNMainRunnableMultipleSteps_bundle.js', array('@FormBuilderCore'));
            $this->Loader->AddStyle('MultipleSteps','js/dist/RNMainRunnableMultipleSteps_bundle.css');
            self::$Dependencies[]='@MultipleSteps';
        }

        if(isset($options->ClientOptions->MultipleSteps->IncludeStepImage)&&$options->ClientOptions->MultipleSteps->IncludeStepImage)
        {
            do_action('rness_multiple_steps_include_step_image');
        }

        if(Sanitizer::GetValueFromPath($options->ClientOptions,['Theme'])!='')
        {
            $this->Loader->AddStyle('theme_'.$options->ClientOptions->Theme,'styles/themes/'.$options->ClientOptions->Theme.'.css');
        }

        self::$Translations=[];
        if(isset($options->Dependencies))
        {
            foreach ($options->Dependencies as $currentDynamicField)
            {
                $field=FieldsDictionary::GetFieldByName($currentDynamicField);
                if($field==null) {
                    $loaded = false;
                    $loaded = apply_filters('allinoneforms_load_dependency',$loaded,$currentDynamicField,$this);
                    if($loaded)
                        continue;
                }


                if($currentDynamicField=='DatePickerField'||$currentDynamicField=='DateRangeField')
                {
                    $this->LibraryManager->AddDate();
                    $this->VarData['TCal']=$this->LibraryManager->GetCalendarTranslations();
                }
                if($currentDynamicField=='TermOfServiceField')
                {
                    $this->LibraryManager->AddDialog();
                }

                if($currentDynamicField=='TooltipLib')
                {
                    $this->LibraryManager->AddTooltip();
                    continue;
                }


                self::$Dependencies[] = '@' . $currentDynamicField;
                $this->Loader->AddScript($currentDynamicField, 'js/dist/RNMain' . $currentDynamicField . '_bundle.js', array('@FormBuilderCore'));
                if ($field != null && $field->HasStyles)
                    $this->Loader->AddStyle($currentDynamicField, 'js/dist/RNMain' . $currentDynamicField . '_bundle.css');
                if($field!=null&&isset($field->HasTranslations)&&$field->HasTranslations)
                    self::$Translations[]=$field->Name;

            }


        }


        $additionalStyles='';
        $fontManager=new FontManager();
        if(isset($this->Options->ServerOptions)) {
            foreach ($this->Options->ServerOptions->ServerDependencies as $currentDependency) {
                if($currentDependency->Type=='Font')
                {
                    $additionalStyles.=$fontManager->GetFontFace($currentDependency->Id);
                }
            }
        }

        $codeToReturn='';
        if($additionalStyles!='')
        {
            $codeToReturn='<style>'.$additionalStyles.'</style>';
        }

        $this->VarData=\apply_filters('rednao-calculated-field-load-vars',$this->VarData);
        $this->Loader->AddRNTranslator(self::$Translations);
        $this->Loader->RemoveScript('runnable-form-builder');
        self::$Dependencies=\apply_filters('allinoneforms_loading_runnable_addons',self::$Dependencies,$this);
        $this->Loader->AddScript('runnable-form-builder','js/dist/RNMainRunnableForm_bundle.js',\array_merge(self::$Dependencies,$this->LibraryManager->dependencies));

        $this->Loader->LocalizeScript('FormOptions_'.$this->Id,'FormBuilderCore','Submission',$this->VarData);
        return $codeToReturn.'<div id="rnef_'.esc_attr($this->Id).'" class="RNEasyFormContainer" data-varid="'.esc_attr($this->Id).'"></div>';
    }

    private function MaybeAddAdditionalOptions()
    {
       /* $options=$this->GetOptions();
        if(isset($options->ClientOptions->ConfirmationOptions->ConfirmationItem)&&\is_array($options->ClientOptions->ConfirmationOptions->ConfirmationItem)&&count($options->ClientOptions->ConfirmationOptions->ConfirmationItem)>0)
        {
            foreach($options->ClientOptions->ConfirmationOptions->ConfirmationItem as $item)
            {
                if($item->ConfirmationType=='page')
                {
                    $pageIntegration=new PageIntegration($this->Loader);
                    if(isset($item->PageId)&&$item->PageId!=''&&$item->PageId>0)
                    {

                        try
                        {
                            $url = $pageIntegration->GetPageURLById($item->PageId);

                            if(!isset($this->VarData['Pages']))
                                $this->VarData['Pages']=array();
                            if(!ArrayUtils::Some($this->VarData['Pages'],function ($item){return $item["Id"]==$item->PageId;}))
                            {
                                $this->VarData['Pages'][]=array("Id"=>$item->PageId,"URL"=>$url);
                            }
                        } catch (Exception $e)
                        {
                            LogManager::Log(LogManager::TYPE_ERROR,"Page with id {$item->PageId} was not found, redirection to that page is not possible");
                        }
                    }

                }
            }
        }*/
    }

    public function SetAsQuickPreview($nonce)
    {
        $this->IsPreview=true;
        $previewUrl=IntegrationURL::PreviewURL();
        $previewItem='';
        if($this->Options->Id>0)
        {
            $previewUrl .= '&formid=' . $this->Options->Id;
            $previewUrl .= '&_nonce=' . $nonce;
            $previewItem = "<li style='text-align: left;list-style-position: inside;margin-left: 15px;'><a href='" . esc_attr($previewUrl) . "'>" . \esc_html("Use this link") . "</a></li>";
        }


        $this->VarData['FormDesignNonce']=wp_create_nonce('rndesign_form_'.$this->FormId);
        $this->VarData['QuickPreviewNonce']=$nonce;
        $this->VarData['QuickPreview']=true;
        $this->VarData['QuickPreviewTitle']="Submission is not supported in quick preview";
        $this->VarData['QuickPreviewMessage']=
            "<p style='margin:0;padding:0;text-align: left;'>".\esc_html(__("To submit a form please do one of the following:"))."</p>".
            "<ul style='margin:0;padding: 0;'>".
                "<li style='text-align: left;list-style-position: inside;margin-left: 15px;'>".\esc_html("Preview the form in the")." <a href='".esc_attr(IntegrationURL::PageURL('rednao_calculation_form'))."'>".\esc_html(__("Form List Page"))."</a></li>".
                $previewItem.
                "<li style='text-align: left;list-style-position: inside;margin-left: 15px;'>".\esc_html("Add the form in a page and preview it from there")."</li>".
            "<ul>"

        ;
    }

    public function LoadEntry($entry)
    {
        $this->VarData['PreviousData']=$entry;
    }

    public function LoadFromId($formId)
    {
        $formRepository=new FormRepository($this->Loader);
        $formOptions=$formRepository->GetForm($formId,true)->Options;

        if($formOptions==null)
            return false;

        $this->LoadForm($formOptions);
        return true;
    }

    public function SetTimeOffset($timeOffset)
    {
        $this->EditOptions->TimeOffset=$timeOffset;
    }

    /**
     * @param $serverOptions ServerOptionsDTO
     * @return mixed|null
     */
    private function ValidateRestrictions($serverOptions)
    {
        if(!isset($serverOptions->Restrictions))
            return '';


        $mustBeLoggedIn= ArrayUtils::Find($serverOptions->Restrictions,function ($item){return $item->Type=="MustBeLoggedIn";});
        if($mustBeLoggedIn!=null)
        {

            $userIntegration = new UserIntegration($this->Loader);
            if ($userIntegration->GetCurrentUserId() != 0)
                return '';


            if ($mustBeLoggedIn == null)
                return '';

            $generator = new HTMLGenerator(new FormBuilder($this->Loader,$this->Options,null),$mustBeLoggedIn->ErrorMessage);
            return $generator->GetInline();
        }


        /** @var NumberOfSubmissionsRestrictionOptionDTO $numberOfSubmissionRestriction */
        $numberOfSubmissions= ArrayUtils::Find($serverOptions->Restrictions,function ($item){return $item->Type=="NumberOfSubmissions";});

        if($numberOfSubmissions!=null)
        {
            $restriction=new MaximumSubmissionNumberRestriction($this->Loader);
            $result=null;
            if(($result=$restriction->ValidateRestriction($numberOfSubmissions,$this->Options->Id)))
            {
                if($result instanceof MessageConfirmationAction)
                {
                    return $result->Title;
                }
                $generator = new HTMLGenerator(new FormBuilder($this->Loader,$this->Options,null), $numberOfSubmissions->ErrorMessage);
                return $generator->GetInline();
            }
        }

        return '';

    }

    private function MaybeIncludeUserInfo()
    {
        //todo: load this info only when the form uses it
        $userInfo=new stdClass();
        $userInfo->Id=get_current_user_id();
        $userInfo->Roles=[];

        if($userInfo->Id>0)
        {
            $user=get_user_by('id',$userInfo->Id);
            if($user!=false)
            {
                $userInfo->Roles=array_values($user->roles);
            }
        }

        $this->VarData['CurrentUser']=$userInfo;

    }

    private function GetAttributes()
    {
        $attributes=[];
        foreach($this->Options->ServerOptions->ServerDependencies as $currentDependency)
        {
            if($currentDependency->Type!='FixedField')
                continue;
            $user=wp_get_current_user();
            switch ($currentDependency->Id)
            {
                case 'UserRole':
                    $attributes['UserRole']=$user==null?'':implode(', ',$user->roles);
                    break;
                case 'CurrentDate':
                    $attributes['CurrentDate']=time();
                    break;
                case 'FirstName':
                    $attributes['FirstName']=$user==null?'':$user->first_name;
                    if($attributes['FirstName']==false)
                        $attributes['FirstName']='';
                    break;
                case 'LastName':
                    $attributes['LastName']=$user==null?'':$user->last_name;
                    if($attributes['LastName']==false)
                        $attributes['LastName']='';
                    break;
                case 'Email':
                    $attributes['Email']=$user==null?'':$user->user_email;
                    if($attributes['Email']==false)
                        $attributes['Email']='';
                    break;
                case 'FullUserName':
                    $attributes['FullUserName']=$user==null?'':$user->user_firstname.' '.$user->user_lastname;
                    if($attributes['FullUserName']==false)
                        $attributes['FullUserName']='';
                    break;
                case 'UserMeta':
                    $value=get_user_meta($user->ID,$currentDependency->Options->Id,true);
                    $attributes['UserMeta_'.$currentDependency->Options->Id]=$value==false?'':$value;
                case 'PostId':
                    $attributes['PostId']=get_the_ID();
                    if(Sanitizer::GetValueFromPath($currentDependency,['Options','IncludeNonce'],false))
                        $attributes['PostId_nonce']=wp_create_nonce('att_PostId_'.$attributes['PostId']);
                    break;

            }
        }

        return $attributes;
    }

}