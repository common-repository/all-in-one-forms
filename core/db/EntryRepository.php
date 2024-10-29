<?php


namespace rednaoeasycalculationforms\core\db;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\FileItem;
use rednaoeasycalculationforms\core\db\core\RepositoryBase;
use rednaoeasycalculationforms\core\Integration\DateIntegration;
use rednaoeasycalculationforms\core\Managers\EntrySaver\AIOEntry;
use rednaoeasycalculationforms\core\Managers\EntrySaver\AIOMeta;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class EntryRepository extends RepositoryBase
{

    public function LoadEntry($entryId)
    {
        $result=$this->DBManager->GetResult('select data,date,reference_id,status,user_id,form_id,sequence,formatted_sequence,total from '.$this->Loader->RECORDS_TABLE.' where entry_id=%d',$entryId);
        if($result==null)
            return null;

        $dateIntegration=new DateIntegration($this->Loader);

        $entryData=new AIOEntry();
        $entryData->EntryId=$entryId;
        $entryData->Reference=$result->reference_id;
        $entryData->Status=$result->status;
        $entryData->Data=\json_decode($result->data);
        $entryData->UserId=$result->user_id;
        $entryData->FormId=$result->form_id;
        $entryData->Sequence=$result->sequence;
        $entryData->CreationDate=$result->date;
        $entryData->UnixDate=strtotime($result->date);
        $entryData->FormattedSequence=$result->formatted_sequence;
        $entryData->Total=Sanitizer::SanitizeNumber($result->total,0);
        return $entryData;
    }

    public function GetEntryIdAndFormByReference($reference)
    {
        $dbManager=new DBManager();
        $entry=$dbManager->GetResult('select form_id FormId,entry_id EntryId from '.$this->Loader->RECORDS_TABLE.' where reference_id=%s',$reference);
        if($entry===null)
            return null;

        return $entry;
    }

    public function DeleteEntryLinks($entryId){
        $this->DBManager->Delete($this->Loader->LINKS,array('entry_id'=>$entryId));
    }

    public function DeleteEntry($entryId)
    {

        do_action('allinoneforms_before_delete_entry', $entryId);


        $this->DeleteEntryLinks($entryId);
        $this->DeleteEntryDetail($entryId);
        $this->DeleteAllMeta($entryId);
        $this->DeleteFilesByEntryId($entryId);
        $this->DBManager->Delete($this->Loader->RECORDS_TABLE,array('entry_id'=>$entryId));


    }

    public function DeleteAllMeta($entryId)
    {
        $this->DBManager->Delete($this->Loader->RECORDS_META,array('entry_id'=>$entryId));
    }

    public function DeleteMetaByEntryAndName($entryId,$metaName)
    {
        $this->DBManager->Delete($this->Loader->RECORDS_META,array('entry_id'=>$entryId,'meta_name'=>$metaName));

    }

    public function GetReference($entryId){
        return $this->DBManager->GetVar('select reference_id from '.$this->Loader->RECORDS_TABLE.' where entry_id=%s',$entryId);

    }

    /**
     * @param $entryReference
     * @param $fileId
     * @return FileItem[]
     */
    public function GetFilesByField($entryReference,$fileId){
        $files= $this->DBManager->GetResults('select file_id FileId,entry_reference EntryReference,file_reference FileReference,
        field_file_id FieldFileId,name Name,physical_name PhysicalName,upload_date UploadDate,mime_type MimeType,file_type FileType,
          file_sequence_id FileSequenceId  from '.
            $this->Loader->RECORDS_FILES.' where field_id=%s and entry_reference=%s' ,$fileId,$entryReference);


        $fileItemList=array();
        foreach($files as $currentFile)
        {
            $fileItem=new FileItem();
            $fileItem->FileId=$currentFile->FileId;
            $fileItem->EntryReference=$currentFile->EntryReference;
            $fileItem->FileReference=$currentFile->FileReference;
            $fileItem->FieldFileId=$currentFile->FieldFileId;
            $fileItem->Name=$currentFile->Name;
            $fileItem->PhysicalName=$currentFile->PhysicalName;
            $fileItem->UploadDate=$currentFile->UploadDate;
            $fileItem->MimeType=$currentFile->MimeType;
            $fileItem->FileType=$currentFile->FileType;
            $fileItem->FileSequenceId=$currentFile->FileSequenceId;

            $fileItemList[]=$fileItem;
        }

        return $fileItemList;
    }

    public function DeleteEntryDetail($entryId)
    {
        $this->DBManager->Delete($this->Loader->RECORDS_DETAIL,array('entry_id'=>$entryId));
    }

    private function DeleteFilesByEntryId($entryId)
    {
        $reference=$this->GetReference($entryId);
        if($reference==null)
            return;

        $result=$this->DBManager->GetResults('select file_id FileId,physical_name PhysicalName,file_type FileType from '.$this->Loader->RECORDS_FILES.' where entry_reference=%s',$reference);
        $fileManager=new FileManager($this->Loader);


        foreach($result as $currentFile)
        {
            $fileManager->DeleteFile($currentFile->FileType,$currentFile->PhysicalName);
        }

        $this->DBManager->Delete($this->Loader->RECORDS_FILES,array('entry_reference'=>$reference));
    }

    public function SaveFormattedId($entryId,$format)
    {

        $dbManager=new DBManager();
        $dbManager->Update($this->Loader->RECORDS_TABLE,array('formatted_sequence'=>$format),array('entry_id'=>$entryId));

    }

    public function SaveUserId($entryId,$format)
    {

        $dbManager=new DBManager();
        $dbManager->Update($this->Loader->RECORDS_TABLE,array('user_id'=>$format),array('entry_id'=>$entryId));

    }

    public function SaveDate($entryId,$format)
    {

        $dbManager=new DBManager();
        $dbManager->Update($this->Loader->RECORDS_TABLE,array('date'=>$format),array('date'=>$entryId));

    }

    public function SaveStatus($entryId, $status)
    {
        $dbManager=new DBManager();
        $dbManager->Update($this->Loader->RECORDS_TABLE,array('status'=>$status),array('entry_id'=>$entryId));
    }

    public function SaveIsVisible($entryId, $isVisible)
    {
        $dbManager=new DBManager();
        $dbManager->Update($this->Loader->RECORDS_TABLE,array('is_visible'=>$isVisible),array('entry_id'=>$entryId));
    }

    /**
     * @param $entryId
     * @param $meta AIOMeta[]
     */
    public function UpdateMetaValues($entryId, $meta)
    {
        $dbManager=new DBManager();
        $dbManager->Update($this->Loader->RECORDS_TABLE,array('meta_values'=>\json_encode($meta)),
            array('entry_id'=>$entryId)
        );

        $dbManager->Delete($this->Loader->RECORDS_META,array('entry_id'=>$entryId));

        foreach($meta as $currentMeta)
        {
            $dbManager->Insert($this->Loader->RECORDS_META,array(
               'entry_id'=>$entryId,
               'meta_name'=>$currentMeta->MetaName,
               'display_value'=>$currentMeta->DisplayValue,
               'display_label'=>$currentMeta->DisplayLabel,
               'meta_value'=>$currentMeta->MetaValue,
               'data_type'=>$currentMeta->DataType,
                'is_visible'=>$currentMeta->IsVisible
            ));
        }

    }


}

