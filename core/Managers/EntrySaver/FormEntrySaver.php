<?php


namespace rednaoeasycalculationforms\core\Managers\EntrySaver;


use Exception;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\OptionsManager;
use rednaoeasycalculationforms\core\db\EntryRepository;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Integration\PageIntegration;
use rednaoeasycalculationforms\core\Integration\UserIntegration;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\ConditionManager\ConditionManager;
use rednaoeasycalculationforms\core\Managers\EmailManager\EmailManager;
use rednaoeasycalculationforms\core\Managers\EntrySaver\ConfirmationActions\ConfirmationActionBase;
use rednaoeasycalculationforms\core\Managers\EntrySaver\ConfirmationActions\MessageConfirmationAction;
use rednaoeasycalculationforms\core\Managers\EntrySaver\ConfirmationActions\RedirectConfirmationAction;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLGenerator;
use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;
use rednaoeasycalculationforms\core\Managers\RestrictionManager\MaximumSubmissionNumberRestriction;
use rednaoeasycalculationforms\core\Managers\RestrictionManager\UniqueRestriction;
use rednaoeasycalculationforms\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rednaoeasycalculationforms\core\Managers\SlateTextGenerator\SlateTextGenerator;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\core\Utils\IdUtils;
use rednaoeasycalculationforms\DTO\BuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\EmailItemOptionsDTO;
use rednaoeasycalculationforms\DTO\NumberOfSubmissionsRestrictionOptionDTO;
use rednaoeasycalculationforms\DTO\UniqueRestrictionOptionDTO;


class FormEntrySaver
{
    /** @var Loader */
    public $Loader;
    public $SaveSuccess;
    /** @var BuilderOptionsDTO */
    public $BuilderOptions;
    /** @var AIOEntry */
    public $Entry;
    /** @var FormBuilder */
    public $FormBuilder;

    /** @var ConfirmationActionBase */
    private $Confirmation;
    private $ContinueInsertion;
    public $OriginalStatus;
    public $FormId;
    public $Sequence;
    private $ContinuePostInsertion=true;
    public function __construct($loader)
    {
        $this->Confirmation=null;
        $this->Loader=$loader;
        $this->ContinueInsertion=true;
        $this->ContinuePostInsertion=true;

    }

    public function Initialize($formId)
    {
        $this->OriginalStatus='';
        $repository=new FormRepository($this->Loader);
        $form=$repository->GetForm($formId,true)->Options;
        if($form==null)
            return false;

        $this->BuilderOptions=$form;
        return true;
    }

    public function StopPostInsertion(){
        $this->ContinuePostInsertion=false;
    }

    public function StopInsertion(){
        $this->ContinueInsertion=false;
    }


    public function LoadFormById($formid){

    }



    public function ProcessEntry($entry,$meta=[])
    {
        $this->SaveSuccess = false;
        $this->BeforeProcessingEntry($entry);

        foreach($meta as $property=>$value)
        {
            $dataType='string';
            if(isset($value['dataType']))
                $dataType=$value['dataType'];
            $displayLabel='';
            if(isset($value['displayLabel']))
                $displayLabel=$value['displayLabel'];
            $displayValue='';
            if(isset($value['displayValue']))
                $displayValue=$value['displayValue'];
            $isVisible=true;
            if(isset($value['isVisible']))
                $isVisible=$value['isVisible'];
            $this->AddMeta($property,$value['value'],$dataType,$displayLabel,$displayValue,$isVisible);
        }

        $this->CreateFormBuilder();
        $this->FormBuilder->Initialize();


        if (!$this->IsEdition()) {
            if($this->BuilderOptions->ServerOptions->ExtendedValidation)
            {
                $result=true;
                try{
                    $result=$this->FormBuilder->Validate();
                }catch (Exception $e)
                {
                    $result=false;
                }

                if(!$result)
                {
                    $error=__("There was an error with the extended validation, please report this issue in the support forum or disable extended validation","all-in-one-forms");
                    if(count($this->FormBuilder->Errors)>0)
                        $error=$this->FormBuilder->Errors[0];
                    return new MessageConfirmationAction($error,__('Extended Validation Error',"all-in-one-forms") , _('Close'), MessageConfirmationAction::$ICON_TYPE_ERROR);
                }
            }

            $result = $this->ValidateRestriction($entry);
            if ($result != null)
                return $result;
        }

        $this->CreateNextSequence();
        $this->GenerateFormattedSequence();


        if($this->BuilderOptions->ServerOptions->ExtendedValidation)
        {
            if(!$this->FormBuilder->CalculationsAreValid())
                throw new FriendlyException('Invalid total, please try again');
        }


        /* todo: fill the original entry with the entry before the edition*/
        $originalEntry=null;
        if ($this->IsEdition())
            do_action('allinoneforms_before_editing_entry', $this,$originalEntry);
        else
            do_action('allinoneforms_before_creating_entry', $this);

        \do_action('allinoneforms_before_saving_to_db', $this);
        if (!$this->ContinueInsertion)
            return $this->Confirmation;
        $this->SaveToDb();
        \do_action('allinoneforms_after_saving_to_db', $this);

        if ($this->IsEdition())
            do_action('allinoneforms_after_editing_entry', $this,$originalEntry);
        else
            do_action('allinoneforms_after_creating_entry', $this);


        if (!$this->ContinuePostInsertion)
            return $this->Confirmation;


        $this->SendEmails();
        $this->SaveSuccess = true;
        if ($this->Confirmation != null)
            return $this->Confirmation;
        return $this->ProcessDefaultConfirmations();
    }

    /**
     * @param $confirmation ConfirmationActionBase
     */
    public function AddConfirmation($confirmation)
    {
        $this->Confirmation=$confirmation;
    }



    public function SetStatus($status)
    {
        $this->Entry->Status=$status;
        if($this->Entry->EntryId>0)
        {
            LogManager::LogDebug('Updating entry '.$this->Entry->EntryId.' status to '.$status);
            $entryRepository=new EntryRepository($this->Loader);
            $entryRepository->SaveStatus($this->Entry->EntryId,$status);
        }
    }

    public function SetIsVisible($isVisible)
    {
        $this->Entry->IsVisible=$isVisible;
        if($this->Entry->EntryId>0)
        {
            $entryRepository=new EntryRepository($this->Loader);
            $entryRepository->SaveIsVisible($this->Entry->EntryId,$isVisible);
        }
    }

    private function FormatSequence($sequenceNumber){
        $entryNumbering=$this->BuilderOptions->ServerOptions->EntryNumbering;
        $numberOfDigits=$entryNumbering->NumberOfDigits;
        $prefix=$entryNumbering->Prefix;
        $suffix=$entryNumbering->Suffix;
        $nextNumber=$sequenceNumber;
        $model=$this->FormBuilder;

        if($numberOfDigits<0)
            $numberOfDigits=0;

        $nextNumber=\str_pad($nextNumber,$numberOfDigits,'0',\STR_PAD_LEFT);

        $slateGenerator=new SlateTextGenerator($model);
        $prefix=$slateGenerator->GetText($prefix);
        $suffix=$slateGenerator->GetText($suffix);

        return $prefix.$nextNumber.$suffix;
    }

    private function SaveToDb()
    {

        $ip='';
        if(isset($_SERVER['HTTP_CLIENT_IP']))
            $ip=$_SERVER['HTTP_CLIENT_IP'];


            $date=date('Y-m-d h:i:s', $this->Entry->UnixDate);
            $this->InsertRecord(array(
                'sequence' => $this->Entry->Sequence,
                'formatted_sequence' => $this->Entry->FormattedSequence,
                'form_id' => $this->Entry->FormId,
                'date' => $date,
                'data' => \json_encode($this->FormBuilder->Serialize()),
                'ip' => $ip,
                'user_id' => $this->Entry->UserId,
                'reference_id' => $this->Entry->ReferenceId,
                'status' => $this->Entry->Status,
                'total' => $this->FormBuilder->GrandTotal,
                'is_visible'=>$this->Entry->IsVisible
            ));
            $this->InsertRecordDetails();

        $this->FormBuilder->ContainerManager->CommitFiles();

    }

    public function GetRestrictionById($restrictionId)
    {

        if(!isset($this->BuilderOptions->ServerOptions->Restrictions))
            return null;
        return ArrayUtils::Filter($this->BuilderOptions->ServerOptions->Restrictions,function ($item)use($restrictionId){return $item->Type==$restrictionId;});
    }

    public function GetExtensionServerOptions($extensionId)
    {
        return ArrayUtils::Find($this->BuilderOptions->ServerOptions->Extensions,function ($item)use($extensionId){return $item->Id==$extensionId;});

    }

    public function IsEdition(){
        return false;
    }

    public function SendEmails($processAsSubmission=false)
    {
        $shouldSend=true;
        $shouldSend=\apply_filters('allinoneforms_should_send_email',$shouldSend,$this);
        if(!$shouldSend)
            return;
        foreach($this->BuilderOptions->ServerOptions->Emails as $currentEmail)
        {
            if(!$this->ShouldSendEmail($currentEmail,$processAsSubmission))
                continue;

            $emailManager=new EmailManager();
            $emailManager->Initialize($this->FormBuilder,$currentEmail);
            if($emailManager->ShouldEmailBeSend())
                $emailManager->Send();
        }
    }

    protected function ProcessDefaultConfirmations()
    {
        $confirmationOptions=$this->BuilderOptions->ServerOptions->ConfirmationOptions->ConfirmationItem;

        if(count($confirmationOptions)==0)
            return null;

        $defaultItem=ArrayUtils::Find($confirmationOptions, function ($x){return $x->EnableCondition==false;});

        $conditionedItems=ArrayUtils::Filter($confirmationOptions, function ($x){return $x->EnableCondition==true;});
        $conditionManager=new ConditionManager();
        foreach($conditionedItems as $currentItem)
        {
            if($conditionManager->ShouldProcess($this->FormBuilder,$currentItem))
            {
                return $this->GenerateConfirmation($currentItem);
            }
        }

        if($defaultItem!=null)
        {
            return $this->GenerateConfirmation($defaultItem);
        }

        return null;

    }

    private function GenerateConfirmation($currentItem)
    {
        switch($currentItem->ConfirmationType)
        {
            case 'message':
                $text='';
                if($currentItem->Content!=null)
                {
                    $html=new HTMLGenerator($this->FormBuilder,$currentItem->Content);
                    $text = $html->GetInline();
                }

                $url='';
                if($this->Loader->IsPR()&&$currentItem->URL!=null&&!$this->IsEdition()&&$currentItem->RedirectAfterConfirm)
                {
                    $url=apply_filters('allinoneforms_get_url_content',$url,$currentItem->URL,$this->FormBuilder);
                }

                return new MessageConfirmationAction($text,$currentItem->Title,$currentItem->ButtonText,'Success',$url);
            case 'url':
                return new RedirectConfirmationAction($currentItem->URL);
            case 'page':
                $pageIntegration=new PageIntegration($this->Loader);
                try{
                    $url=$pageIntegration->GetPageURLById($currentItem->PageId);
                    return new RedirectConfirmationAction($url);
                }catch(Exception $e){
                    LogManager::Log(LogManager::TYPE_ERROR,'Page with id '.$currentItem->PageId.' was not found');
                }
                break;

        }
        return null;
    }

    protected function InsertRecord($data)
    {
        $db=new DBManager();
        $data['meta_values']=\json_encode($this->Entry->Meta);
        $entryId=$db->Insert($this->Loader->RECORDS_TABLE,$data);

        $this->Entry->EntryId=$entryId;
        foreach($this->Entry->Meta as $currentMeta)
        {
            $currentMeta->EntryId=$this->Entry->EntryId;
            $db->Insert($this->Loader->RECORDS_META,array(
                'entry_id'=>$this->Entry->EntryId,
                'meta_name'=>$currentMeta->MetaName,
                'meta_value'=>$currentMeta->MetaValue,
                'data_type'=>$currentMeta->DataType,
                'is_visible'=>$currentMeta->IsVisible
            ));
        }
    }

    /**
     * @param $name
     * @param null $defaultValue
     * @return AIOMeta
     */
    public function GetMeta($name,$defaultValue=null)
    {
        $value=ArrayUtils::Find($this->Entry->Meta,function ($item)use($name){return $item->MetaName==$name;});
        if($value==null)
            return $defaultValue;

        return $value;
    }

    public function AddMeta($name,$value,$dataType='string',$displayLabel='',$displayValue='',$isVisible=true)
    {
        $meta=ArrayUtils::Find($this->Entry->Meta,function ($item) use($name){return $item->MetaName==$name;});
        if($meta==null)
        {
            $meta=new AIOMeta();
            $meta->EntryId=$this->Entry->EntryId;
            $this->Entry->Meta[]=$meta;

        }

        $meta->DataType=$dataType;
        $meta->MetaValue=$value;
        $meta->MetaName=$name;
        $meta->IsVisible=$isVisible;
        $meta->DisplayValue=$displayValue;
        $meta->DisplayLabel=$displayLabel;

        if($this->Entry->EntryId>0)
        {
            $entryRepository = new EntryRepository($this->Loader);
            $entryRepository->UpdateMetaValues($this->Entry->EntryId, $this->Entry->Meta);
        }



    }

    protected function InsertRecordDetails()
    {
        $db=new DBManager();

        foreach($this->FormBuilder->ContainerManager->GetLineItems() as $item)
        {

            $detail=array(
                'entry_id'=>$this->FormBuilder->GetEntryId(),
                'field_id'=>$item->FieldId,
                'form_id'=>$this->FormBuilder->GetId(),
                'value'=>$item->Value,
                'unit_price'=>$item->UnitPrice,
                'total_field_price'=>$item->TotalFieldPrice,
                'uniq_id'=>$item->UniqId,
                'type'=>$item->Type
            );

            if($item->ExValue1!==null)
                $detail['exvalue1']=$item->ExValue1;
            if($item->ExValue2!==null)
                $detail['exvalue2']=$item->ExValue2;
            if($item->ExValue3!==null)
                $detail['exvalue3']=$item->ExValue3;
            if($item->ExValue4!==null)
                $detail['exvalue4']=$item->ExValue4;
            if($item->ExValue5!==null)
                $detail['exvalue5']=$item->ExValue5;
            if($item->ExValue6!==null)
                $detail['exvalue6']=$item->ExValue6;

            if($item->DateValue!==null)
                $detail['datevalue']=$item->DateValue;
            if($item->DateValue2!==null)
                $detail['datevalue2']=$item->DateValue2;

            if($item->NumericValue!==null)
                $detail['numericvalue']=$item->NumericValue;
            if($item->NumericValue2!==null)
                $detail['numericvalue2']=$item->NumericValue2;
            if($item->SubType!=null)
                $detail['subtype']=$item->SubType;



            $db->Insert($this->Loader->RECORDS_DETAIL,$detail);
        }
    }

    public function GetNextSequence()
    {
        $options=new OptionsManager();
        $formSequence=$this->Loader->Prefix.'_sequence_'.$this->Entry->FormId;
        $currentId=$options->GetOption($formSequence,0);

        $currentId++;
        $options->SaveOptions($formSequence,$currentId);
        $this->Sequence=$currentId;
        return $currentId;
    }

    /**
     * @param $entry
     */
    public function CreateFormBuilder()
    {
        $this->FormBuilder = new FormBuilder($this->Loader, $this->BuilderOptions, $this->Entry);
        $this->FormBuilder->SetOriginalStatus($this->OriginalStatus);
    }

    protected function BeforeProcessingEntry($entry)
    {
        $this->Entry=new AIOEntry();
        $this->Entry->Data=$entry->Data;
        $this->Entry->Total=$entry->Total;
        $this->Entry->IsVisible=true;

        $this->Entry->Status=$this->BuilderOptions->ServerOptions->DefaultStatus;
        $this->Entry->UnixDate=\time();
        $this->Entry->ReferenceId=IdUtils::GetUniqueId();
        $this->Entry->FormId=$this->BuilderOptions->Id;
        $userManager=new UserIntegration($this->Loader);
        $this->Entry->UserId=$userManager->GetCurrentUserId();



    }

    public function CreateNextSequence(){
        $this->Entry->Sequence=$this->GetNextSequence();
    }

    protected function GenerateFormattedSequence()
    {
        $this->Entry->FormattedSequence=$this->FormatSequence($this->Entry->Sequence);

    }

    private function ValidateRestriction($entry)
    {
        $formBuilder=new FormBuilder($this->Loader, $this->BuilderOptions, $entry);
        $formBuilder->Initialize();

        /** @var UniqueRestrictionOptionDTO $uniqueRestriction */
        $uniqueRestrictionList=$this->GetRestrictionById('Unique');


        foreach($uniqueRestrictionList as $uniqueRestriction)
        {

            $uniqueRestrictionManager = new UniqueRestriction($this->Loader);
            $field = $formBuilder->GetRootForm()->GetContainerManager()->GetFieldById($uniqueRestriction->FieldId, true, true);

            if ($field == null || !$field->IsUsed() || $field->GetLineItems() == null)
                continue;

            if (!\is_numeric($uniqueRestriction->NumberOfTimes) || $uniqueRestriction->NumberOfTimes <= 0)
                continue;

            if (!$uniqueRestrictionManager->ValidateRestriction($field, $uniqueRestriction->NumberOfTimes))
            {
                $htmlGenerator = new HTMLGenerator($this->FormBuilder,$uniqueRestriction->ErrorMessage);

                return new MessageConfirmationAction($htmlGenerator->GetInline(),'' , _('Close'), MessageConfirmationAction::$ICON_TYPE_ERROR);
            }
        }

        /** @var NumberOfSubmissionsRestrictionOptionDTO $numberOfSubmissions */
        $numberOfSubmissions=$this->GetRestrictionById('NumberOfSubmissions');
        if($numberOfSubmissions!=null&&count($numberOfSubmissions)>0)
        {
            $numberOfSubmissions=$numberOfSubmissions[0];
            $restriction=new MaximumSubmissionNumberRestriction($this->Loader);
            if(($result=$restriction->ValidateRestriction($numberOfSubmissions,$formBuilder->Options->Id)))
            {
                if($result instanceof MessageConfirmationAction)
                {
                    return new MessageConfirmationAction($result->Title,'',_('Close'),MessageConfirmationAction::$ICON_TYPE_ERROR);
                }
                $generator = new HTMLGenerator($this->FormBuilder,$numberOfSubmissions->ErrorMessage);
                return new MessageConfirmationAction($generator->GetInline(),'',_('Close'),MessageConfirmationAction::$ICON_TYPE_ERROR);
            }

        }


        return null;

    }

    /**
     * @param $currentEmail EmailItemOptionsDTO
     */
    protected function ShouldSendEmail($currentEmail,$processAsSubmission=false)
    {

        if($this->IsEdition()&&!$processAsSubmission)
            return false;



        $conditionManager=new ConditionManager();
        return $conditionManager->ShouldProcess($this->FormBuilder, $currentEmail->Condition);

    }


}