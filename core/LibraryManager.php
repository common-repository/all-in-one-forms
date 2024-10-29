<?php


namespace rednaoeasycalculationforms\core;


use rednaoeasycalculationforms\core\FieldsDictionary\FieldsDictionary;
use rednaoeasycalculationforms\core\Integration\UserIntegration;

class LibraryManager
{
    /** @var Loader */
    public $Loader;

    public $dependencies = [];

    public function __construct($loader)
    {
        $this->Loader = $loader;
    }

    public static function GetInstance(){
        return new LibraryManager(apply_filters('allinoneforms_get_loader',null));
    }



    public function GetCalendarTranslations(){
        return [
            "weekdays"=>[
                "shorthand"=>[__("Sun","all-in-one-forms"),__("Mon","all-in-one-forms"),__("Tue","all-in-one-forms"),__("Wed","all-in-one-forms"),__("Thu","all-in-one-forms"),__("Fri","all-in-one-forms"),__("Sat","all-in-one-forms")],
                "longhand"=>[__("Sunday","all-in-one-forms"),__("Monday","all-in-one-forms"),__("Tuesday","all-in-one-forms"),__("Wednesday","all-in-one-forms"),__("Thursday","all-in-one-forms"),__("Friday","all-in-one-forms"),__("Saturday","all-in-one-forms")]
            ],
            "months"=>[
                "shorthand"=>[__("Jan","all-in-one-forms"),__("Feb","all-in-one-forms"),__("Mar","all-in-one-forms"),__("Apr","all-in-one-forms"),
                    _x("May",'short_month',"all-in-one-forms"),__("Jun","all-in-one-forms"),__("Jul","all-in-one-forms"),__("Aug","all-in-one-forms"),__("Sep","all-in-one-forms"),__("Oct","all-in-one-forms"),__("Nov","all-in-one-forms"),__("Dec","all-in-one-forms")],
                "longhand"=>[__("January","all-in-one-forms"),__("February","all-in-one-forms"),__("March","all-in-one-forms"),__("April","all-in-one-forms"),_x("May",'long_month',"all-in-one-forms"),__("June","all-in-one-forms"),__("July","all-in-one-forms"),__("August","all-in-one-forms"),__("September","all-in-one-forms"),__("October","all-in-one-forms"),__("November","all-in-one-forms"),__("December","all-in-one-forms")]
            ],
            "firstDayOfWeek"=>0,
            "rangeSeparator"=>__(" to ","all-in-one-forms"),
            "time_24hr"=>false,
            'amPM'=>["AM","PM"]
        ];
    }

    public function Swiper(){
        $this->Loader->AddScript('RNSwiper','js/lib/swiper/swiper-bundle.min.js');
        $this->Loader->AddStyle('Swiper','js/lib/swiper/swiper-bundle.min.css');
        $this->AddDependency('@RNSwiper');
    }

    public function AddContextMenu(){
        self::AddLit();
        $this->Loader->AddScript('ContextMenu','js/dist/RNMainContextMenu_bundle.js',array('@lit'));
        $this->Loader->AddStyle('ContextMenu','js/dist/RNMainContextMenu_bundle.css');
        $this->AddDependency('@ContextMenu');
    }
    public function LoadFormulaFunctions(){

    }

    private function FileUploader()
    {
        self::AddLit();
        $this->Loader->AddScript('FileUploader', 'js/dist/RNMainFileUploader_bundle.js',array('@lit'));
        $this->Loader->AddStyle('FileUploader', 'js/dist/RNMainFileUploader_bundle.css');
        $this->AddDependency('@FileUploader');
    }

    private  function AddSlidePanel()
    {
        self::AddLit();
        $this->Loader->AddScript('Slidepanel','js/dist/RNMainSlidePanel_bundle.js',array('@lit'));
        $this->Loader->AddStyle('Slidepanel','js/dist/RNMainSlidePanel_bundle.css');
        $this->AddDependency('@Slidepanel');
    }

    public function GetDependencyHooks(){
        $hooks=[];
        foreach($this->dependencies as $currentDependency)
        {
            $hooks[]=\str_replace('@',$this->Loader->Prefix.'_',$currentDependency);
        }
        return $hooks;
    }

    public function AddConditionDesigner()
    {

        self::AddLit();
        self::AddFormBuilderCore();
        $this->Loader->AddScript('conditiondesigner','js/dist/RNMainConditionDesigner_bundle.js',array('@lit','@FormBuilderCore','@formulaParser'));
        $this->Loader->AddStyle('conditiondesigner','js/dist/RNMainConditionDesigner_bundle.css');
        $this->AddDependency('@conditiondesigner');

        $userIntegration=new UserIntegration($this->Loader);
        $this->Loader->LocalizeScript('rnConditionDesignerVar','conditiondesigner','alloinoneforms_list_users',[
            "Roles"=>$userIntegration->GetRoles()
        ]);
    }

    protected function AddDependency($dependency)
    {
        if (!in_array($dependency, $this->dependencies))
            $this->dependencies[] = $dependency;
    }

    public function AddConditionalFieldSet(){
        self::AddSwitchContainer();
        $this->Loader->AddScript('conditionalfieldset','js/dist/RNMainConditionalFieldSet_bundle.js',array('@switchcontainer'));
        $this->AddDependency('@conditionalfieldset');
    }

    public function AddSingleLineGenerator()
    {
        $this->Loader->AddScript('singlelinegenerator','js/dist/RNMainSingleLineGenerator_bundle.js');
        $this->AddDependency('@singlelinegenerator');

    }

    public function AddHTMLGenerator(){
        self::AddLit();
        $this->Loader->AddScript('htmlgenerator','js/dist/RNMainHTMLGenerator_bundle.js',array('@FormBuilderCore','@lit'));

    }

    public function AddSwitchContainer(){
        self::AddLit();
        $this->Loader->AddScript('switchcontainer','js/dist/RNMainSwitchContainer_bundle.js',array('@lit'));
        $this->AddDependency('@switchcontainer');

    }

    public function AddFormulaParser(){
        $this->AddFormBuilderCore();
        $this->Loader->AddScript('formulaParser','js/dist/RNMainParser_bundle.js',array('@FormBuilderCore'));
        $this->Loader->AddStyle('formulaParser','js/dist/RNMainParser_bundle.css');
        $this->AddDependency('@formulaParser');
    }

    public function AddInputs(){
        self::AddLit();
        self::AddCore();
        self::AddSelect();
        self::AddCoreUI();
        $this->Loader->AddScript('date','js/lib/date/flatpickr.js',array('@lit'));
        $this->Loader->AddStyle('date','js/lib/date/flatpickr.min.css');
        $this->Loader->AddScript('inputs','js/dist/RNMainInputs_bundle.js',array('@lit','@select','@date','@CoreUI'));
        $this->Loader->AddStyle('inputs','js/dist/RNMainInputs_bundle.css');

        $this->AddDependency('@inputs');

    }

    public function AddBasicTextEditor(){
        self::AddProseMirror();
        $this->Loader->AddScript('BasicTextEditor','js/dist/RNMainBasicTextEditor_bundle.js',array('@ProseMirror'));
        $this->Loader->AddStyle('BasicTextEditor','js/dist/RNMainBasicTextEditor_bundle.css');
        $this->AddDependency('@BasicTextEditor');
    }
    public function AddAlertDialog(){
        self::AddLit();
        self::AddCore();
        self::AddDialog();
        $this->Loader->AddScript('AlertDialog','js/dist/RNMainAlertDialog_bundle.js',array('@lit','@Dialog','@Core'));
        $this->Loader->AddStyle('AlertDialog','js/dist/RNMainAlertDialog_bundle.css');
        $this->AddDependency('@AlertDialog');

    }

    public function AddTextEditor(){
        self::AddLit();
        self::AddDialog();
        self::AddInputs();
        self::AddAccordion();
        self::AddBasicTextEditor();
        self::AddSingleLineGenerator();
        $this->Loader->AddScript('texteditor','js/dist/RNMainTextEditor_bundle.js',array('@lit','@Dialog','@inputs','@ProseMirror','@BasicTextEditor'));
        $this->Loader->AddStyle('texteditor','js/dist/RNMainTextEditor_bundle.css');
        $this->AddDependency('@texteditor');

    }

    public function AddProseMirror(){
        $this->Loader->AddScript('ProseMirror','js/dist/RNMainProseMirror_bundle.js');
        $this->AddDependency('@ProseMirror');
    }
    public function AddCore(){
        self::AddLoader();
        self::AddLit();
        $this->Loader->AddScript('Core', 'js/dist/RNMainCore_bundle.js', array('@loader', '@lit'));
        $this->AddDependency('@Core');
    }


    public function AddTooltip(){
        self::AddLit();
        $this->Loader->AddScript('Tooltip','js/dist/RNMainTooltipLib_bundle.js',array('@lit'));
        $this->Loader->AddStyle('Tooltip','js/dist/RNMainTooltipLib_bundle.css');

        $this->AddDependency('@Tooltip');
    }
    public function AddFormulas(){
        self::AddFormBuilderCore();
        $this->Loader->AddScript('Formula','js/dist/RNMainFormulaCore_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@Formula');
    }

    public function AddFormBuilderDesigner(){
        self::AddLit();
        self::AddCore();
        self::AddCoreUI();
        self::AddWPTable();
        self::AddDialog();
        self::AddPreMadeDialog();
        self::AddTabs();
        self::AddSpinner();
        self::AddFormulaParser();
        self::AddConditionDesigner();
        self::AddTooltip();
        self::AddSlidePanel();
        self::FileUploader();
        self::AddAlertDialog();

        self::AddConditionalFieldSet();
        self::AddSingleLineGenerator();
        self::AddSwitchContainer();
        self::AddAccordion();
        self::AddSelect();
        self::AddHTMLGenerator();
        self::AddTextEditor();
        self::AddDate();
        self::AddInputs();
        self::AddFormBuilderCore();

        self::AddMultipleSteps();
        self::AddFormulas();
        self::AddContextMenu();

        do_action('rness_multiple_steps_include_step_image');
        $fields=FieldsDictionary::GetFields();
        $translations=[];
        foreach($fields as $currentField)
        {

            $this->Loader->AddScript($currentField->Name,'js/dist/RNMain'.$currentField->Name.'_bundle.js',array('@FormBuilderCore'));
            $this->AddDependency('@'.$currentField->Name);

            if($currentField->HasStyles)
            {
                $this->Loader->AddStyle($currentField->Name, 'js/dist/RNMain' . $currentField->Name . '_bundle.css');
            }

            if($currentField!=null&&isset($currentField->HasTranslations)&&$currentField->HasTranslations)
                $translations[]=$currentField->Name;
        }

        $this->Loader->AddRNTranslator($translations);

        $this->Loader->AddScript('CurrentValueCalculator','js/dist/RNMainCurrentValueCalculator_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@CurrentValueCalculator');
        $this->Loader->AddScript('FormulaPerItemCalculator','js/dist/RNMainFormulaPerItemCalculator_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@FormulaPerItemCalculator');
        $this->Loader->AddScript('QuantityCalculator','js/dist/RNMainQuantityCalculator_bundle.js',array('@FormBuilderCore'));
        $this->Loader->AddScript('QuantityByValueCalculator','js/dist/RNMainQuantityByValueCalculator_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@QuantityCalculator');
        $this->Loader->AddScript('PricePerItemCalculator','js/dist/RNMainPricePerItemCalculator_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@PricePerItemCalculator');


        $this->Loader->AddScript('RequireCondition','js/dist/RNMainRequiredCondition_bundle.js',array('@FormBuilderCore'));
        $this->Loader->AddScript('SkipRepeaterItemCondition','js/dist/RNMainSkipRepeaterItemCondition_bundle.js',array('@FormBuilderCore'));
        $this->Loader->AddScript('ChangeOptionsCondition','js/dist/RNMainChangeOptionsCondition_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@ChangeOptionsCondition');
        $this->Loader->AddScript('ChangeOptionsPriceCondition','js/dist/RNMainChangeOptionsPriceCondition_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@ChangeOptionsPriceCondition');

        $this->Loader->AddScript('ShowHideStepCondition','js/dist/RNMainShowHideStepCondition_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@ShowHideStepCondition');


        $this->Loader->AddScript('FormBuilderDesigner', 'js/dist/RNMainBuilder_bundle.js',array_merge( array('@FormBuilderCore'),$this->dependencies));
        $this->Loader->AddStyle('FormBuilderDesigner', 'js/dist/RNMainBuilder_bundle.css');
        $this->dependencies=[];
        $this->AddDependency('@FormBuilderDesigner');


    }

    public function AddFormBuilderCore(){
        self::AddCore();
        self::AddCoreUI();
        self::AddDialog();
        $this->Loader->AddScript('FormBuilderCore', 'js/dist/RNMainFormBuilderCore_bundle.js', array('@Core','@Dialog','@CoreUI'));
        $this->Loader->AddStyle('FormBuilderCore', 'js/dist/RNMainFormBuilderCore_bundle.css');
        $this->AddDependency('@FormBuilderCore');
    }

    public function AddLoader()
    {
        $this->Loader->AddScript('loader', 'js/lib/loader.js');
        $this->AddDependency('@loader');
    }

    public function AddSelect(){
        $this->Loader->AddScript('select','js/lib/tomselect/js/tom-select.complete.js');
        $this->Loader->AddStyle('select','js/lib/tomselect/css/tom-select.bootstrap5.css');
        $this->AddDependency('@select');
    }


    public function AddLit()
    {
        self::AddLoader();
        $this->Loader->AddScript('lit', 'js/dist/RNMainLit_bundle.js', array('@loader'));
        $this->AddDependency('@lit');
    }

    public function AddCoreUI()
    {
        self::AddCore();
        $this->Loader->AddScript('CoreUI', 'js/dist/RNMainCoreUI_bundle.js', array('@Core'));
        $this->Loader->AddStyle('CoreUI', 'js/dist/RNMainCoreUI_bundle.css');

        $this->AddDependency('@CoreUI');
    }

    public function AddTranslator($fileList)
    {
        $this->Loader->AddRNTranslator($fileList);
        $this->AddDependency('@RNTranslator');
    }

    public function AddDialog()
    {
        self::AddLit();
        self::AddCore();
        $this->Loader->AddScript('Dialog', 'js/dist/RNMainDialog_bundle.js', array('@lit','@Core'));
        $this->Loader->AddStyle('Dialog', 'js/dist/RNMainDialog_bundle.css');
        $this->AddDependency('@Dialog');
    }

    public function AddContext(){
        self::AddLit();
        $this->Loader->AddScript('Context','js/dist/RNMainContext_bundle.js');
        $this->Loader->AddStyle('Context','js/dist/RNMainContext_bundle.css');
    }

    public function AddPreMadeDialog(){
        self::AddDialog();
        self::AddSpinner();
        $this->Loader->AddScript('PreMadeDialog', 'js/dist/RNMainPreMadeDialogs_bundle.js', array('@Dialog'));

    }

    public function AddDate(){
        self::AddLit();;
        $this->Loader->AddScript('date','js/lib/date/flatpickr.js',array('@lit'));
        $this->Loader->AddStyle('date','js/lib/date/flatpickr.min.css');
        $this->AddDependency('@date');
    }

    public function AddAccordion()
    {
        self::AddLit();
        $this->Loader->AddScript('Accordion', 'js/dist/RNMainAccordion_bundle.js', array('@lit'));
        $this->Loader->AddStyle('Accordion', 'js/dist/RNMainAccordion_bundle.css');
        $this->AddDependency('@Accordion');
    }


    public function AddTabs()
    {
        $this->AddLit();
        $this->Loader->AddScript('Tabs', 'js/dist/RNMainTabs_bundle.js', array('@lit'));
        $this->Loader->AddStyle('Tabs', 'js/dist/RNMainTabs_bundle.css');

        $this->AddDependency('@Tabs');
    }

    public function AddSpinner(){
        self::AddLit();
        self::AddCore();
        $this->Loader->AddScript('Spinner', 'js/dist/RNMainSpinnerButton_bundle.js', array('@lit','@Core'));
        $this->Loader->AddStyle('Spinner', 'js/dist/RNMainSpinnerButton_bundle.css');
    }

    public function AddWPTable()
    {
        self::AddCore();
        $this->Loader->AddScript('WPTable', 'js/dist/RNMainWPTable_bundle.js', array('@Core'));
        $this->Loader->AddStyle('WPTable', 'js/dist/RNMainWPTable_bundle.css');
        $this->AddDependency('@WPTable');
    }

    public function AddMultipleSteps(){
        self::AddCore();
        self::AddFormBuilderCore();
        $this->Loader->AddScript('MultipleSteps','js/dist/RNMainRunnableMultipleSteps_bundle.js',array('@Core','@FormBuilderCore'));
        $this->Loader->AddStyle('MultipleSteps','js/dist/RNMainRunnableMultipleSteps_bundle.css');

        $this->Loader->AddScript('MultipleSteps','js/dist/RNMainRunnableMultipleSteps_bundle.js',array('@Core','@FormBuilderCore'));
        $this->Loader->AddStyle('MultipleSteps','js/dist/RNMainRunnableMultipleSteps_bundle.css');
        $this->AddDependency('@MultipleSteps');
    }
}