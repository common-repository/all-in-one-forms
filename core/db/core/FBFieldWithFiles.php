<?php


namespace rednaoeasycalculationforms\core\db\core;


use rednaoeasycalculationforms\core\db\EntryRepository;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldWithPrice;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;

class FBFieldWithFiles extends FBFieldWithPrice
{
    /** @var  FileItem[] */
    public $FilesToAdd;

    /** @var FileItem[] */
    private $OriginalFiles;

    public function __construct($loader, $fbColumn, $options, $entry = null)
    {
        parent::__construct($loader, $fbColumn, $options, $entry);
        $this->FilesToAdd=array();
        $this->OriginalFiles=null;
    }


    /**
     * @param $fileItem FileItem
     */
    public function AddFile($fileItem)
    {
        $this->FilesToAdd[]=$fileItem;

    }

    public function GetOriginalFiles(){
        if($this->OriginalFiles!=null)
            return $this->OriginalFiles;
        if($this->GetRootForm()->IsEdition)
        {
            $repository = new EntryRepository($this->Loader);
            $this->OriginalFiles = $repository->GetFilesByField($this->GetRootForm()->GetReference(), $this->GetId());

        }else
            $this->OriginalFiles=array();

        return $this->OriginalFiles;

    }



    public function CommitFiles()
    {
        $dbManager=new DBManager();
        $originalFiles=$this->GetOriginalFiles();

        foreach($originalFiles as $file)
        {
            if($file->FileId>0)
            {
                $currentFile = ArrayUtils::Find($this->FilesToAdd, function ($item) use ($file) {
                    return $item->FileSequenceId == $file->FileSequenceId;
                });

                if($currentFile==null)
                {
                    $entryRepository=new EntryRepository($this->Loader);
                    $fileManager=new FileManager($this->Loader);

                    $fileManager->DeleteFile($file->FileType,$file->PhysicalName);
                    $dbManager->Delete($this->Loader->RECORDS_FILES,array('file_sequence_id'=>$file->FileSequenceId));

                }


            }
        }


        foreach($this->FilesToAdd as $currentFile)
        {
            if($currentFile->FileId==0)
            {
                $dbManager->Insert($this->Loader->RECORDS_FILES, array(
                    'entry_reference' => $currentFile->EntryReference,
                    'file_reference' => $currentFile->FileReference,
                    'field_id' => $currentFile->FieldId,
                    'field_file_id' => $currentFile->FieldFileId,
                    'name' => $currentFile->Name,
                    'physical_name' => $currentFile->PhysicalName,
                    'upload_date' => $currentFile->UploadDate,
                    'mime_type' => $currentFile->MimeType,
                    'file_type' => $currentFile->FileType,
                    'file_sequence_id' => $currentFile->FileSequenceId
                ));
            }
        }
    }


}