<?php


namespace rednaoeasycalculationforms\ajax;


use Exception;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class EntryUtilsAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'Submission';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPublic('getpublicfileupload','GetFileUpload',false);
        $this->RegisterPublic('getpublicsignature','GetSignature',false);

    }

    public function GetSignature(){
        $ref=$this->GetOptional('ref','');
        $view=$this->GetOptional('View','download');

        $fileParts=\explode('__',$ref);
        if(count($fileParts)!=2)
            $this->SendErrorMessage('Invalid reference');

        $fileManager=new FileManager($this->Loader);

        $fileData=null;
        try{
            $fileData=$fileManager->GetFileData($fileParts[0],$fileParts[1]);
        }catch (Exception $e)
        {

        }

        if($fileData==null)
            $this->SendErrorMessage('Invalid reference');


        if(!\file_exists($fileData->Path))
        {
            $this->SendErrorMessage('File does not exists');
        }

        $file=array(
            'Mime'=>$fileData->Mime,
            'FileName'=>$fileData->Name,
        );

        $file['Content']= \base64_encode(\file_get_contents($fileData->Path));
        $this->SendSuccessMessage($file);
        exit;
    }

    public function GetFileUpload(){
        if(!isset($_GET['ref']))
            $this->SendErrorMessage('Invalid operation');



        $ref=Sanitizer::SanitizeWithRegex($_GET['ref'],'/[a-z0-9]+__[a-z0-9]+/i');

        $fileParts=\explode('__',$ref);
        if(count($fileParts)!=2)
            $this->SendErrorMessage('Invalid reference');

        $fileManager=new FileManager($this->Loader);

        $fileData=null;
        try{
            $fileData=$fileManager->GetFileData($fileParts[0],$fileParts[1]);
        }catch (Exception $e)
        {

        }

        if($fileData==null)
            $this->SendErrorMessage('Invalid reference');


        if(!\file_exists($fileData->Path))
        {
            $this->SendErrorMessage('File does not exists');
        }


        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-type: '.$fileData->Mime);
        header('Content-disposition: attachment; filename='.$fileData->Name);


        //Define file size
        header('Content-Length: ' . filesize($fileData->Path));
        readfile($fileData->Path);
        exit;



    }
}