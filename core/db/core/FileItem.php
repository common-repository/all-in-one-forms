<?php


namespace rednaoeasycalculationforms\core\db\core;


class FileItem
{
    public $FileId;
    public $EntryReference;
    public $FileReference;
    public $FieldFileId;
    public $Name;
    public $PhysicalName;
    public $UploadDate;
    public $MimeType;
    public $FileType;
    public $FileSequenceId;

    public function __construct()
    {
        $this->FileId=0;
        $this->EntryReference='';
        $this->FileReference='';
        $this->FieldId='';
        $this->FieldFileId='';
        $this->Name='';
        $this->PhysicalName='';
        $this->UploadDate='';
        $this->MimeType='';
        $this->FileType='';
        $this->FileSequenceId='';
    }


}