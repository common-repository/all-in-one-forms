<?php


namespace rednaoeasycalculationforms\pr\Managers\Editor;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\EntryRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\EmailManager\EmailManager;
use rednaoeasycalculationforms\core\Managers\EntrySaver\AIOEntry;
use rednaoeasycalculationforms\core\Managers\EntrySaver\FormEntrySaver;
use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;
use rednaoeasycalculationforms\Managers\QueryManager\QueryManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;


class FormEntryEditor extends FormEntrySaver
{
    public $DefaultStatus;
    public $EntryId;

    public function __construct($loader)
    {
        parent::__construct($loader);
    }

    public function Initialize($entryId)
    {
        $this->EntryId=$entryId;
        $dbManager=new DBManager();
        $formId=$dbManager->GetResult('select form_id FormId from '.$this->Loader->RECORDS_TABLE.' where entry_id=%s',$entryId);
        if($formId==null)
        {
            LogManager::LogError('Form with entry id '.$entryId.' was not found ');
            return false;
        }

        $formId=$formId->FormId;
        $this->FormId=$formId;

        $queryManager=new QueryManager($this->Loader,$formId);
        $queryManager->CreateWhereGroup()->AddEntryId($entryId);
        $result=$queryManager->GetResult();
        if($result==null)
        {
            LogManager::LogError('Entry '.$entryId.' was not found');
            return false;
        }

        $this->Entry=$result;
        if(!parent::Initialize($this->Entry->FormId))
        {
            LogManager::LogError('Could not initialie entry editor');
            return false;
        }

        $this->OriginalStatus=$result->Status;

        $this->CreateFormBuilder();
        $this->FormBuilder->Initialize();
        return true;
    }

    public function CreateNextSequence()
    {
        return;
    }

    public function UpdateField($fieldId,$value)
    {
        switch ($fieldId) {
            case '_formatted_sequence':
                $this->Entry->FormattedSequence=Sanitizer::SanitizeString($value);
                return;
            case '_status':
                $this->Entry->Status=Sanitizer::SanitizeString($value);
                return;
            case '_date':
                $this->Entry->UnixDate=Sanitizer::SanitizeNumber($value);
                return;
            case '_created_by':
                $this->Entry->UserId=Sanitizer::SanitizeNumber($value);
                return;
        }

        $field=$this->FormBuilder->GetFieldById($fieldId,false,false,false);
        if($field==null)
            return;

        $field->Entry=$value;

        for($i=0;$i<count($this->FormBuilder->Entry->Data);$i++)
        {
            $fieldData=$this->FormBuilder->Entry->Data[$i];
            if($fieldData->Id==$fieldId)
            {
                $this->FormBuilder->Entry->Data[$i]=$value;
                return;
            }
        }

        $this->FormBuilder->Entry->Data[]=$value;




    }

    public function UpdateAndSaveEntryInformation($fieldId, $value)
    {
        $fieldToUse='';
        if($fieldId!=''&&$fieldId[0]=='_') {
            switch ($fieldId) {
                case '_formatted_sequence':
                    $fieldToUse = 'formatted_sequence';
                    break;
                case '_status':
                    $fieldToUse = 'status';
                    break;
                case '_date':
                    $fieldToUse = 'date';
                    $value=date('c',$value);
                    break;
                case '_created_by':
                    $fieldToUse = 'user_id';
                    break;
                default:
                    $fieldToUse='';
            }

            if($fieldToUse=='')
                throw new FriendlyException('Unknown field '.$fieldId);

            global $wpdb;



            if($wpdb->update($this->Loader->RECORDS_TABLE,[$fieldToUse=>$value],array('entry_id'=>$this->EntryId))===false)
                throw new FriendlyException('Could not update record');

            if($this->Entry!=null)
            {
                $this->Entry->{$fieldId}=$value;
            }

            if($fieldId=='_created_by')
            {
                $userData=get_user_by('ID',$value);
                $email='';
                $name='';

                if($userData!=false)
                {
                    $email=$userData->user_email;
                    $name=$userData->nickname;
                }

                $this->Entry->UserName=$name;
                $this->Entry->UserEmail=$email;
            }

        }



    }

    protected function SetEntry($entryId,$oldEntryData)
    {
        $this->EntryId=$entryId;
        $this->OldEntryData=$oldEntryData;
        $this->DefaultStatus=$oldEntryData->Status;
        return $this;
    }

    public function CreateFormBuilder()
    {
        parent::CreateFormBuilder();
        $this->FormBuilder->SetIsEdition();
    }

    public function LoadEntry($data=null)
    {

        $dbmanager=new DBManager();

        $result=$dbmanager->GetResult("select data Data,form_id FormId,user_id UserId, date Date,sequence Sequence,formatted_sequence FormattedSequence, user_id UserId,ip IP,status Status,reference_id ReferenceId from ".$this->Loader->RECORDS_TABLE.' where entry_id=%s',$this->EntryId);

        if($result==null)
            return false;

        if(!$this->Initialize($result->FormId))
            return false;

        $data=\json_decode($result->Data);
        $this->CreateFormBuilder();
        $this->DefaultStatus=$result->Status;

        return true;
    }

    public function IsEdition()
    {
        return true;
    }


    public function SendEmail($emailId)
    {

        foreach($this->BuilderOptions->ServerOptions->Emails as $currentEmail)
        {
            if($currentEmail->Id==$emailId)
            {
                $emailManager=new EmailManager();
                $emailManager->Initialize($this->FormBuilder,$currentEmail);
                $emailManager->Send();
            }
        }

    }

    protected function BeforeProcessingEntry($entry)
    {
        $entryToEdit=new AIOEntry();
        $entryToEdit->Data=$entry->Data;
        $entryToEdit->Total=$entry->Total;

        $entryToEdit->EntryId=$this->Entry->EntryId;
        $entryToEdit->Sequence=$this->Entry->Sequence;
        $entryToEdit->Status=$entry->Status;
        $entryToEdit->UnixDate=$this->Entry->UnixDate;
        $entryToEdit->ReferenceId=$this->Entry->ReferenceId;
        $entryToEdit->FormId=$this->Entry->FormId;
        $entryToEdit->UserId=$this->Entry->UserId;
        $entryToEdit->FormattedSequence=$this->Entry->FormattedSequence;
        $this->Entry=$entryToEdit;



    }


    protected function InsertRecord($data)
    {
        $dbmanager=new DBManager();
        $dbmanager->Update($this->Loader->RECORDS_TABLE,$data,array('entry_id'=>$this->Entry->EntryId));

        $entryRepository=new EntryRepository($this->Loader);
        $entryRepository->DeleteEntryDetail($this->Entry->EntryId);
    }

    protected function InsertRecordDetails()
    {
        $entry=new EntryRepository($this->Loader);
        $entry->DeleteEntryDetail($this->Entry->EntryId);
        parent::InsertRecordDetails(); // TODO: Change the autogenerated stub
    }

    protected function GenerateFormattedSequence()
    {
        return;
    }


}