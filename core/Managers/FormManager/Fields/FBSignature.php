<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\FBFieldWithFiles;
use rednaoeasycalculationforms\core\db\core\FileItem;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Integration\FileIntegration;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLEmailContext;
use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;
use rednaoeasycalculationforms\core\Utils\IdUtils;
use Twig\Markup;


class FBSignature extends FBFieldWithFiles
{
    public function GetValue()
    {
        return $this->GetEntryValue('Value','');
    }

    public function InternalToText()
    {
        $filemanager=new FileManager($this->Loader);
        return $filemanager->GetDownloadLink($this->Entry->FileId,$this->Entry->FileReference);
    }

    public function GetBlockType()
    {
        return 'block';
    }

    public function PrepareForSerialization()
    {
        if($this->Entry==null||$this->GetEntryValue('Value','')==='')
            return array();

        $fileManager=new FileManager($this->Loader);

        $formRepository=new FormRepository($this->Loader);
        $fileItem=null;
        if($this->GetEntryValue('WasAlreadyUploaded',false))
        {
            unset($this->Entry->WasAlreadyUploaded);
            $files=$this->GetOriginalFiles();
            if(\count($files)>0)
                $fileItem=$files[0];
        }else
        {
            $fileItem=new FileItem();
            $fileItem->MimeType='image/png';
            $fileItem->EntryReference=$this->GetRootForm()->GetReference();
            $fileItem->UploadDate=date('c');
            $fileItem->FileReference=IdUtils::GetUniqueId();
            $fileItem->FileSequenceId=$formRepository->GetNextFileId();
            $fileItem->Name='';
            $fileItem->PhysicalName=$fileManager->MoveSignature($this->GetEntryValue('Value',''));;
            $fileItem->FileType='signature';
            $fileItem->FieldId=$this->Options->Id;
        }

        if($fileItem==null)
            return array();

        $this->Entry->Value=$fileItem->PhysicalName;
        $this->Entry->EntryReference=$fileItem->EntryReference;
        $this->Entry->FileReference=$fileItem->FileReference;
        $this->Entry->UploadDate=$fileItem->UploadDate;
        $this->Entry->FileId=$fileItem->FileSequenceId;

        $this->AddFile($fileItem);

    }

    public function GetSignatureURL($context){
        $filemanager=new FileManager($this->Loader);
        if($this->Entry==null)
            return '';
        if($context!=null&&$context instanceof HTMLEmailContext) {
            $fileData=$filemanager->GetFileData($this->Entry->FileId,$this->Entry->FileReference);
            $id=$context->AddInlineImage($fileData->Path);
            return 'cid:'.$id;
        }else {
            if($context!=null&&$context->ShouldConvertLocalToFilePath()) {
                $path= $filemanager->GetFileData($this->Entry->FileId, $this->Entry->FileReference)->Path;
                return $path;
            }
            else
                return $filemanager->GetDownloadLink($this->Entry->FileId,$this->Entry->FileReference);
        }

    }

    public function GetLineItems()
    {
        $entry= parent::GetLineItems()[0];

        $entry->Value=$this->Entry->Value;
        $entry->ExValue1=$this->Entry->FileReference;
        $entry->DateValue=$this->Entry->UploadDate;

        return array($entry);
    }

    public function GetHTMLTemplate($context=null)
    {
        return 'core/Managers/FormManager/Fields/FBSignature.twig';
    }



}