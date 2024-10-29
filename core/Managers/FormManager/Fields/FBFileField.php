<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\db\core\FBFieldWithFiles;
use rednaoeasycalculationforms\core\db\core\FileItem;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLEmailContext;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\core\Utils\IdUtils;
use Twig\Markup;


class FBFileField extends FBFieldWithFiles
{
    public $FilesToDelete;
    public function GetValue()
    {
        return $this->GetEntryValue('Value');
    }

    public function PrepareForSerialization()
    {
        parent::PrepareForSerialization();
        $fileManager=new FileManager($this->Loader);



        $originalFiles=$this->GetOriginalFiles();

        $formRepository=new FormRepository($this->Loader);

        foreach($this->Entry->Value as $file)
        {
            $name=$file->Id;
            if($file->FileId>0)
            {
                $currentFile=ArrayUtils::Find($originalFiles,function ($item)use($file){return $item->FileSequenceId==$file->FileId;});
                if($currentFile!=null)
                {
                    $file->Value=$currentFile->PyshicalFileName;
                    $file->Extension=pathinfo($file->PyshicalFileName,\PATHINFO_EXTENSION);
                    $file->EntryReference=$currentFile->EntryReference;
                    $file->UploadDate=$currentFile->UploadDate;
                    $file->FileReference=$currentFile->FileReference;
                    $file->Mime=$currentFile->MimeType;
                    $file->FileId=$currentFile->FileSequenceId;

                    $this->AddFile($currentFile);
                    continue;

                }else{
                    throw new FriendlyException('File was not uploaded correctly','The file '.$file->Name.' was not found');
                }
            }
            if(!isset($_FILES[$name]))
            {
                throw new FriendlyException('File was not uploaded correctly','Field with problem: '.$file->Name);
            }




            $mime=$fileManager->GetFileMimeType($_FILES[$name]['tmp_name']);



            $file->Value=$fileManager->UploadFile($name);
            $file->Extension=pathinfo($file->PyshicalFileName,\PATHINFO_EXTENSION);
            $file->EntryReference=$this->GetRootForm()->GetReference();
            $file->UploadDate=date('c');
            $file->FileReference=IdUtils::GetUniqueId();
            $file->Mime=$mime;
            $file->FileId=$formRepository->GetNextFileId();

            $fileItem=new FileItem();
            $fileItem->MimeType=$mime;
            $fileItem->EntryReference=$this->GetRootForm()->GetReference();
            $fileItem->UploadDate=$file->UploadDate;
            $fileItem->FileReference=$file->FileReference;
            $fileItem->FileSequenceId=$file->FileId;
            $fileItem->Name=$file->Name;
            $fileItem->PhysicalName=$file->Value;
            $fileItem->FileType='file';
            $fileItem->FieldId=$this->Options->Id;

            $this->AddFile($fileItem);





        }

    }

    public function GetLineItems()
    {
        $lineItems= parent::GetLineItems()[0];

        $lineItemList=array();
        foreach($this->Entry->Value as $file)
        {

            $fileLineItem=$lineItems->CloneItem();

            $fileLineItem->UnitPrice=0;
            $fileLineItem->Value=$file->Name;
            $fileLineItem->ExValue1=$file->FileReference;
            $fileLineItem->ExValue2=$file->EntryReference;
            $fileLineItem->ExValue3=$file->Extension;
            $fileLineItem->DateValue=$file->UploadDate;
            $fileLineItem->UnitPrice=$file->total->Price;
            $lineItemList[]=$fileLineItem;


        }
        return $lineItemList;
    }

    public function InternalToText()
    {
        $values=$this->GetEntryValue('Value',array());
        $fileManager=new FileManager($this->Loader);
        $urls=[];
        foreach($values as $currentValue)
        {
            $urls[]= $fileManager->GetDownloadLink($currentValue->FileId,$currentValue->FileReference);

        }

        return \implode(', ',$urls);
    }

    public function GetItems($context=null){
        $items=[];
        $filemanager=new FileManager($this->Loader);
        if($this->Entry==null||$this->Entry->Value==null||!is_array($this->Entry->Value))
            return [];
        foreach($this->Entry->Value as $currentItem)
        {
            $isImage=false;
            $url=$filemanager->GetDownloadLink($currentItem->FileId,$currentItem->FileReference);
            if(strpos(strtolower($currentItem->Mime),'image')!==false)
            {
                $isImage=true;

                if($context instanceof HTMLEmailContext) {
                    $fileData=$filemanager->GetFileData($currentItem->FileId,$currentItem->FileReference);
                    $id=$context->AddInlineImage($fileData->Path);
                    $url='cid:'.$id;
                }
            }




            $items[]=[
                "Label"=>$currentItem->Name,
                "IsImage"=>$isImage,
                "URL"=>$url
            ];
        }

        return $items;

    }

    public function GetHTMLTemplate($context = null)
    {
        return 'core/Managers/FormManager/Fields/FBFileField.twig';

    }


}