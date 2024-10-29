<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;

use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\ComparisonSource;
use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\NoneValueComparator;
use rednaoeasycalculationforms\core\Managers\ConditionManager\ConditionManager;
use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\CalculatorBase;
use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\CalculatorFactory;
use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\NoneCalculator;
use rednaoeasycalculationforms\core\Managers\FormManager\FBColumn;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\LineItems\Core\LineItem;
use rednaoeasycalculationforms\core\Managers\SerializationManager\SanitizationManager;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\DTO\FieldBaseOptionsDTO;
use rednaoeasycalculationforms\DTO\ShowHideConditionOptionsDTO;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use Twig\Markup;

abstract class FBFieldBase implements ComparisonSource
{
    static $LINE_ITEMS_UNIQ_ID=0;

    /** @var FieldBaseOptionsDTO */
    public $Options;
    /** @var FBColumn */
    public $Column;
    public $Entry;
    /** @var CalculatorBase */
    public $Calculator;
    /** @var Loader */
    public $Loader;
    public $LineItemsUniqId=0;
    private $_isVisible=null;
    public function __construct($loader, $fbColumn, $options,$entry=null)
    {
        $this->Loader=$loader;
        $this->Column=$fbColumn;
        $this->Options=$options;

        $this->Entry=null;
        if($entry==null&&$this->Column!=null&&$this->Column->Row->Form->GetFieldsEntryData()!=null)
            foreach ($this->Column->Row->Form->GetFieldsEntryData() as $currentEntry )
            {
                if(!\is_array($currentEntry)&&$currentEntry->Id==$this->Options->Id)
                    $this->Entry=$currentEntry;
            }
        else
            $this->Entry=$entry;

        if(isset($this->Options->PriceType))
        {
            $this->Calculator=CalculatorFactory::GetCalculator($this);
        }else
            $this->Calculator=new NoneCalculator($this);

    }

    public function IsFieldContainer(){
        return property_exists($this,'ContainerManager');
    }

    protected function CanSanitize(){
        return false;
    }

    /**
     * @param $sanitizer SanitizationManager
     * @return void
     */
    protected function InternalSanitize($sanitizer){
        $sanitizer->AddStringSanitizer('Label');
        $sanitizer->AddStringSanitizer('Type');
        $sanitizer->AddNumericSanitizer('Id');
    }

    public function Initialize(){
        $this->SanitizeEntry();
    }

    public function SanitizeEntry(){
        if($this->CanSanitize())
        {
            $sanitizer=new SanitizationManager();
            $this->InternalSanitize($sanitizer);
            $this->Entry=$sanitizer->Sanitize($this->Entry);
        }



    }



    public function IsVisible()
    {
        if($this->_isVisible===null)
        {
            if($this->GetRootForm()->IsMultipleSteps()&&$this->GetRootForm()->GetIsStepVisible($this->Column->Row->Options->StepId)==false)
            {
                $this->_isVisible=false;
                return false;
            }
            $form=null;
            $form=$this->GetForm();
            if(method_exists($form,'IsVisible')&&!$form->IsVisible())
            {
                $this->_isVisible=false;
                return false;
            }



            /** @var ShowHideConditionOptionsDTO $condition */
            $conditionlist=$this->GetConditionByType('ShowHide');
            if(count($conditionlist)>0)
            {
                $condition=$conditionlist[0];
                $conditionManager=new ConditionManager();
                $result=$conditionManager->ShouldProcess($this->GetForm(),$condition);
                if($condition->ShowWhenTrue)
                {
                    if($result)
                        $this->_isVisible=true;
                    else
                        $this->_isVisible=false;
                }else{
                    if($result)
                        $this->_isVisible=false;
                    else
                        $this->_isVisible=true;
                }
            }else
                $this->_isVisible=true;

        }

        return $this->_isVisible;



    }

    public function MaybeExecuteCondition($conditionType,$defaultValue=false)
    {
        $condition=ArrayUtils::Find($this->Options->Conditions,function ($item)use($conditionType){return $item->Type==$conditionType;});
        if($condition==null)
            return $defaultValue;





    }

    public function CommitFiles(){

    }

    public function PrepareForSerialization(){

    }

    public function GetOptionValue($optionName,$defaultValue)
    {
        if(!isset($this->Options->$optionName))
            return $defaultValue;
        return $this->Options->$optionName;
    }

    public function GetComparator(){
        return new NoneValueComparator($this->GetForm(),$this);
    }

    public function GetConditionByType($conditionType)
    {
        if(!isset($this->Options->Conditions))
            return array();

        return ArrayUtils::Filter($this->Options->Conditions,function ($item)use($conditionType){return $item->Type==$conditionType;});
    }

    public function AddError($error)
    {
        $this->GetRootForm()->AddError($error);
    }

    public function IsRequired()
    {
        return $required=Sanitizer::GetBooleanValueFromPath($this,['Options','Required'],false);
    }
    public function Validate(){
        $required=$this->IsRequired();

        if($required&&$this->IsVisible()&&!$this->IsUsed())
        {
            $this->AddError('The field '.$this->Options->Id.' is required');
            return false;
        }

        if(isset($this->ContainerManager))
        {
            foreach($this->ContainerManager->GetFields(false,false,false) as $field)
                if(!$field->Validate())
                    return false;
        }

        if(isset($this->Calculator)&&!$this->Calculator->GetIsValid()) {
            $this->AddError('The calculation for field '.$this->Options->Id.' does not match');
            return false;
        }
        return true;
    }

    public function GetEntryValue($path,$default='',$entryObject=null){
        if($entryObject!==null)
            $entry=$entryObject;
        else
            $entry=$this->Entry;
        if($entry==null||!isset($entry->$path))
            return $default;

        return $entry->$path;
    }

    /**
     * @return \rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder
     */
    public function GetForm(){
        if(isset($this->Column))
            if(isset($this->Column->Row))
                if(isset($this->Column->Row->Form))
                {
                    return $this->Column->Row->Form;
                }

        return null;
    }

    public function GetPrice(){

        if(isset($this->Options->PriceType)&& $this->Options->PriceType=='none')
        {
            return \floatval($this->ToText());
        }
        return $this->Calculator->GetPrice();

    }

    public function GetRootForm(){
        if($this->GetForm()==null)
            return null;
        return $this->GetForm()->GetRootForm();

    }

    public function GetRegularPrice(){
        return 0;
    }

    public function GetBlockType(){
        return 'inline';
    }

    public function GetId(){
        return $this->Options->Id;
    }
    public function GetValue(){
        return $this->GetEntryValue('Value');
    }

    /**
     * @return LineItem[]
     */
    public function GetLineItems(){

        if($this->Entry==null||!isset($this->Entry->Id))
            return null;

        if($this->LineItemsUniqId==0)
            $this->LineItemsUniqId=++self::$LINE_ITEMS_UNIQ_ID;

        $lineItem=new LineItem();
        $lineItem->UniqId=$this->LineItemsUniqId;
        $lineItem->FieldId=$this->Entry->Id;
        if(isset($this->Entry->Value))
            $lineItem->Value=$this->Entry->Value;
        $lineItem->TotalFieldPrice=$this->GetEntryValue('Price',0);
        $lineItem->UnitPrice=$this->GetEntryValue('Price',0);
        $lineItem->Type=$this->Options->Type;
        return array($lineItem);
    }

    public function GetStoresInformation(){
        return true;
    }

    public function IsUsed(){

        if($this->GetRootForm()!=null&&$this->GetRootForm()->IsTest&&$this->GetStoresInformation())
            return true;

        return $this->InternalIsUsed();
    }

    public function InternalIsUsed(){
        $value=$this->GetValue();
        if(\is_array($value))
            return count($value)>0;
        return $this->GetValue()!==null&&$this->GetValue()!=='';
    }
    public function GetIndex(){
        return 0;
    }

    public function ToText()
    {
        if($this->GetRootForm()->IsTest)
            return '[Test Value]';
        if(!$this->IsUsed())
            return '';
        return $this->InternalToText();
    }

    protected function InternalToText(){
        return $this->GetEntryValue('Value');

    }
    public function ToNumber(){
        $text=$this->ToText();
        if(!\is_numeric($text))
            return 0;

        return \floatval($text);
    }

    public function GetSubText($patId){
        return Sanitizer::GetStringValueFromPath($this->Entry,['Value',$patId],'');
    }

    public function GetSubFieldHTMLTemplate($context=null){
        return 'core/Managers/FormManager/Fields/FBSubFieldFieldBase.twig';
    }

    public function GetHTMLTemplate($context=null){
        return 'core/Managers/FormManager/Fields/FBFieldBase.twig';
    }

    /**
     * @return string
     */
    public function GetHtml($context=null){
        return new Markup($this->GetRootForm()->Loader->GetTwigManager()->Render($this->GetHTMLTemplate($context),$this,array('Context'=>$context)),"UTF-8");
    }

    public function GetSubHtml($context=null,$pathId=''){
        return new Markup($this->GetRootForm()->Loader->GetTwigManager()->Render($this->GetSubFieldHTMLTemplate($context),$this,array('Context'=>$context,"PathId"=>$pathId)),"UTF-8");
    }

    public function GetText(){
        return $this->ToText();
    }

    public function GetNumber(){
        return $this->ToNumber();
    }




    public function CommitCreation()
    {
    }

    public function GetLabel(){
        if(isset($this->Options->Label)&& trim($this->Options->Label) != '')
            return $this->Options->Label;
        return '';


    }

    public function GetSubSections(){
        return [];
    }

    public function GetColumnById($pathId)
    {
        foreach($this->GetSubSections() as $currentSection)
        {
            if($currentSection->PathId==$pathId)
                return $currentSection->Column;
        }

        return '';
    }


}
