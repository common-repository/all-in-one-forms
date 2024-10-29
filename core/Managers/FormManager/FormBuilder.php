<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager;




use DOMDocument;
use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\Loader;

use rednaoeasycalculationforms\core\Managers\ConditionManager\ConditionManager;
use rednaoeasycalculationforms\core\Managers\EntrySaver\AIOEntry;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerManager;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\DTO\BuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\FBRowOptionsDTO;
use rednaoeasycalculationforms\DTO\MultipleStepItemDTO;
use rednaoeasycalculationforms\DTO\ShowHideStepConditionOptionsDTO;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class FormBuilder implements ContainerDataRetriever
{
    /** @var BuilderOptionsDTO */
    public $Options;
    /** @var FBRow[] */
    public $Rows;
    /** @var AIOEntry */
    public $Entry;
    public $Errors=[];
    public $UnitPrice;
    public $GrandTotal;
    public $Quantity;
    /** @var FBFieldBase[] */
    public $Fields;
    /** @var Loader */
    public $Loader;
    /** @var ContainerManager */
    public $ContainerManager;
    /** @var array */
    private $LineItems;
    public $DebugModeEnabled;
    private $SerializedEntry;
    private $CurrencyOption;
    public $IsTest;
    public $IsEdition;
    public $OriginalStatus;
    public $IsInitialized=false;
    private $stepVisibility=[];
    /**
     * FormBuilder constructor.
     * @param $loader
     * @param $options
     * @param $entry AIOEntry
     */
    public function __construct($loader,$builderOptions,$entry=null)
    {
        $this->Loader=$loader;
        $this->Options=$builderOptions;
        $this->IsTest=false;
        $this->IsEdition=false;
        $this->ContainerManager=new ContainerManager($this);
        $this->Fields=array();
        $this->Entry=$entry;
        $this->Rows=array();
        $this->SerializedEntry=null;
        $this->CurrencyOption=null;

        $this->GrandTotal=0;
        $this->UnitPrice=0;
        $this->Quantity=0;
        $this->LineItems=null;
        $this->OriginalStatus='';

        if($this->Loader!=null)
            require_once $this->Loader->DIR.'vendor/autoload.php';

        if($builderOptions!=null)
            $this->DebugModeEnabled=$this->Options->FormBuilder->ClientOptions->EnableDebug;


    }

    public function GetId(){
        return $this->Options->Id;
    }
    public function GetName(){
        return $this->Options->Name;
    }

    public function SetOriginalStatus($status)
    {
        $this->OriginalStatus=$status;
        return $this;
    }

    public function SetEntry($entry){
        $this->Entry=$entry;
        $this->Fields=[];
        $this->Initialize();
    }
    public function SetIsEdition(){
        $this->IsEdition=true;
        return $this;
    }

    public function SetIsTest(){
        $this->IsTest=true;
        return $this;
    }

    public function SetEntryId($entryId)
    {
        $this->Entry->EntryId=$entryId;
    }

    public function GetEntryId(){
        return $this->Entry->EntryId;
    }

    public function SetSubmissionDate($date)
    {
        $this->Entry->UnixDate=\strtotime($date);
    }

    public function GetSubmissionDate(){
        return date('c', $this->Entry->UnixDate);
    }

    public function GetFormattedSubmissionDate(){
        return date('m/j/Y',$this->Entry->UnixDate);
    }

    public function SetSubmissionNumber($submissionNumber)
    {
        $this->Entry->Sequence=$submissionNumber;
    }

    public function GetSubmissionNumber(){
        return $this->Entry->Sequence;
    }

    public function FormatCurrency($amount)
    {
        if($this->CurrencyOption==null)
        {
            $repository=new SettingsRepository($this->Loader);
            $this->CurrencyOption=$repository->GetCurrency();
        }

        if(!\is_numeric($amount))
            $amount='0';

        if(\is_string($amount))
            $amount=\floatval($amount);

        $formatted=\number_format($amount,$this->CurrencyOption->Decimals,$this->CurrencyOption->DecimalSeparator,$this->CurrencyOption->ThousandSeparator);

        $finalFormatted=\str_replace('%2$s',$formatted,$this->CurrencyOption->Format);
        $finalFormatted=\str_replace('%1$s',$this->CurrencyOption->Symbol,$finalFormatted);

        return $finalFormatted;


    }

    public function Validate(){
        foreach($this->GetFields() as $field)
            $field->Validate();

        $unitPrice=0;
        $quantity=0;
        $grandTotal=0;
        foreach($this->ContainerManager->GetFields(false,false,false) as $field)
        {
            $unitPrice=Sanitizer::SanitizeNumber(Sanitizer::GetValueFromPath($field,['Entry','UnitPrice']));
            $quantity=Sanitizer::SanitizeNumber(Sanitizer::GetValueFromPath($field,['Entry','Quantity']));
            $grandTotal+=$unitPrice*$quantity;
        }


        if($grandTotal!=$this->Entry->Total)
            $this->AddError('Invalid total, please try again');
        return count($this->Errors)==0;

    }

    public function GetIsStepVisible($stepId)
    {
        if(!isset($this->stepVisibility[$stepId]))
        {
            $this->stepVisibility[$stepId]=true;
            /** @var MultipleStepItemDTO $step */
            $step=ArrayUtils::Find($this->Options->FormBuilder->ClientOptions->MultipleSteps->Steps,function ($item)use($stepId){return $item->Id==$stepId;});
            if($step!=null)
            {
                /** @var ShowHideStepConditionOptionsDTO $condition */
                $condition=ArrayUtils::Find($step->Conditions,function ($item){return $item->Type=='ShowHideStep';});
                if($condition!=null)
                {
                    $manager=new ConditionManager();
                    $result=$manager->ShouldProcess($this,$condition);
                    if($condition->ShowWhenTrue)
                    {
                        if($result==true)
                            $this->stepVisibility[$stepId]=true;
                        else
                            $this->stepVisibility[$stepId]=false;
                    }else{
                        if($result==true)
                            $this->stepVisibility[$stepId]=false;
                        else
                            $this->stepVisibility[$stepId]=true;
                    }

                }
            }
        }
        return $this->stepVisibility[$stepId];
    }
    public function IsMultipleSteps()
    {
        return Sanitizer::GetValueFromPath($this->Options,['FormBuilder','ClientOptions','MultipleSteps'])!=null;

    }
    public function AddError($message)
    {
        $this->Errors[]=$message;

    }
    public function GetReference(){
        return $this->Entry->ReferenceId;
    }
    public function SetReference($reference)
    {
        $this->Entry->ReferenceId=$reference;
    }

    public function CalculationsAreValid()
    {

        $unitPrice=0;
        $quantity=0;
        $grandTotal=0;
        foreach($this->ContainerManager->GetFields(false,false,false) as $field)
        {
            $unitPrice=Sanitizer::SanitizeNumber(Sanitizer::GetValueFromPath($field,['Entry','UnitPrice']));
            $quantity=Sanitizer::SanitizeNumber(Sanitizer::GetValueFromPath($field,['Entry','Quantity']));
            $grandTotal+=$unitPrice*$quantity;
        }


        if($grandTotal!=$this->Entry->Total)
            return false;

        return true;

    }

    public function Initialize(){
        $this->IsInitialized=true;
        $this->Rows=[];
        foreach($this->Options->FormBuilder->Rows as $row)
            $this->Rows[]=new FBRow($this->Loader,$this,$row,$this->GetFieldsEntryData());

        foreach($this->Rows as $Row)
            foreach ($Row->Columns as $Column)
                $this->Fields[]=$Column->Field;


         foreach($this->Rows as $currentRow)
        {
            $currentRow->Initialize();
        }

        if($this->Entry!=null)
             $this->ExecuteCalculations();

        return $this;
    }

    public function GetForm(){
        return null;
    }

    public function GetRootForm(){
        return $this;
    }

    public function GetPriceOfNotDependantFields()
    {
        $total=0;
        foreach($this->Fields as $field)
        {
            if(!$field->Calculator->GetDependsOnOtherFields())
                $total+=$field->Calculator->GetPrice();
        }

        return $total;
    }

    public function GetExtensionServerOptions($extensionId)
    {
        if($this->Options->ServerOptions==null||$this->Options->ServerOptions->Extensions==null)
            return null;
        return ArrayUtils::Find($this->Options->ServerOptions->Extensions,function ($item)use($extensionId){return $item->Id==$extensionId;});
    }


    protected function ExecuteCalculations()
    {
        $this->GrandTotal=$this->Entry->Total;
     /*
        foreach($this->Fields as $field)
        {
            if(!$field->Calculator->GetDependsOnOtherFields())
                $field->Calculator->ExecuteAndUpdate();
        }


        foreach($this->Fields as $field)
        {
            if($field->Calculator->GetDependsOnOtherFields())
                $field->Calculator->ExecuteAndUpdate();
        }



        foreach($this->ContainerManager->GetFields(false,false,false) as $field)
        {
            $this->UnitPrice+=$field->Calculator->GetPrice();
        }

        foreach($this->ContainerManager->GetFields(false,false,false) as $field)
        {
            $this->Quantity+=$field->Calculator->GetQuantity();
        }

        if(!ArrayUtils::Some($this->ContainerManager->GetFields(false,false,false),
            function ($item){
            return isset($item->Options->PriceType)&&( $item->Options->PriceType=="quantity"||$item->Options->PriceType=="quantity_per_day");}))
        {
            $this->Quantity=1;
        }

        $this->GrandTotal=$this->UnitPrice*$this->Quantity;

*/
    }



    /**
     * @param $document DOMDocument
     */
    public function GetHtml($context=null){
        return $this->ContainerManager->GetHtml($context);

    }


    /**
     * @inheritDoc
     */
    public function GetRows()
    {
        return $this->Rows;
    }

    public function Serialize()
    {
        if($this->SerializedEntry==null)
        {
            $this->ContainerManager->PrepareForSerialization();
            $this->SerializedEntry=array();
            foreach ($this->ContainerManager->GetFields(false,false,false) as $field)
            {
                if (!$field->IsUsed())
                    continue;
                $this->SerializedEntry[] = $field->Entry;
            }
        }

        return $this->SerializedEntry;

    }

    /**
     * @param $serverOptions Server
     * @param $entrySequence
     */
    public function FormatEntryId($serverOptions,$entrySequence){

    }

    public function CommitFiles(){
        $this->ContainerManager->CommitFiles();
    }

    public function GetContainerManager()
    {
        return $this->ContainerManager;
    }

    public function GetFields($includeFieldsOfRepeaters=false, $includeFieldsOfGroupPanel=true) {
        if(!$this->IsInitialized)
            $this->Initialize();
        return $this->ContainerManager->GetFields($includeFieldsOfRepeaters,false,$includeFieldsOfGroupPanel);
    }

    /**
     * @param $fieldId
     * @param $repeaterSearchType false|"EntryAll"|"EntryFirst"|"Field"
     * @return FBFieldBase
     */
    public function GetFieldById($fieldId,$searchInChildContainer=false,$searchInParentContainers=false,$repeaterSearchType=false)
    {
        return $this->ContainerManager->GetFieldById($fieldId,$searchInChildContainer,$searchInParentContainers,$repeaterSearchType);
    }

    /**
     * @param $fieldId
     * @return \rednaoeasycalculationforms\DTO\FieldBaseOptionsDTO|null
     */
    public function GetFieldOptionsById($fieldId)
    {
        return $this->PrivateGetFieldOptions($this->Options->FormBuilder->Rows,$fieldId);
    }

    /**
     * @param $rows FBRowOptionsDTO[]
     */
    private function PrivateGetFieldOptions($rows,$fieldId)
    {
        foreach($rows as $currentRow)
        {
            foreach($currentRow->Columns as $currentColumn)
            {
                if($currentColumn->Field==null)
                    continue;
                if($currentColumn->Field->Id==$fieldId)
                {
                    return $currentColumn->Field;
                }

                if(isset($currentColumn->Rows))
                {
                    $field=$this->PrivateGetFieldOptions($currentColumn->Rows,$fieldId);
                    if($field!=null)
                        return $field;
                }

                if(isset($currentColumn->RepeaterItemTemplate)&&isset($currentColumn->RepeaterItemTemplate->Rows)) {
                    $field=$this->PrivateGetFieldOptions($currentColumn->RepeaterItemTemplate->Rows,$fieldId);
                    if($field!=null)
                        return $field;
                }

            }
        }

    }







    public function GetLoader()
    {
        return $this->Loader;
    }

    public function ParseSingleLineText($text)
    {
        $generator=new SingleLineGenerator($this);
        return $generator->GetText($text);
    }

    public function GetIcon($iconName)
    {
        foreach($this->Options->FormBuilder->ClientOptions->Icons as $icon){
            if($icon->Name==$iconName)
                return [
                    'Width'=>$icon->icon[0],
                    'Height'=>$icon->icon[1],
                    'Path'=>$icon->icon[4]
                ];
        };

        return null;
    }

    public function GetFieldsEntryData()
    {
        return Sanitizer::GetValueFromPath($this->Entry,['Data']);
    }
}