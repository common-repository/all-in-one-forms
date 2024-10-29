<?php


namespace rednaoeasycalculationforms\core\Managers\FileManager;



use Exception;
use Imagick;
use ImagickPixel;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Integration\FileIntegration;
use rednaoeasycalculationforms\core\Integration\IntegrationURL;
use rednaoeasycalculationforms\core\Loader;

class FileManager
{
    /** @var Loader */
    public $Loader;

    private $_rootPath='';
    private $fileIntegration;

    public function __construct($loader)
    {
        $this->Loader = $loader;
        $this->fileIntegration=new FileIntegration($loader);

    }


    public function GetMapsFolderRootPath()
    {
        $tempFolder=$this->GetRootFolderPath().'maps/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }


    public function GetTextualImageRootPath()
    {
        $tempFolder=$this->GetRootFolderPath().'textualimage/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }

    public function GetSignatureFolderRootPath()
    {
        $tempFolder=$this->GetRootFolderPath().'signature/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }

    public function GetImageFolderRootPath()
    {
        $tempFolder=$this->GetRootFolderPath().'image/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }
    public function GetSafeFileName($path,$name)
    {
        $name=sanitize_file_name($name);
        $ext = pathinfo($name, \PATHINFO_EXTENSION);
        $name = pathinfo($name, \PATHINFO_FILENAME);
        if($ext!='')
            $ext='.'.$ext;
        $newName=$name.$ext;
        if(\file_exists($path.$newName))
        {
            $count=1;

            do
            {
                $newName=$name.'_'.$count.$ext;
                $count++;
            }while((\file_exists($path.$newName)));
        }

        return $newName;

    }

    public function GetLoggerPath(){
        $tempFolder=$this->GetRootFolderPath().'log/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }

    public function GetRootFolderPath()
    {
        if($this->_rootPath=='')
        {
            $this->_rootPath=\str_replace('\\','/', $this->fileIntegration->GetUploadDir().'/'.$this->Loader->Prefix.'/');
            $this->MaybeCreateFolder($this->_rootPath,false);
        }
        return $this->_rootPath;
    }


    public function GetFontPath(){
        $fontFolder=$this->GetRootFolderPath().'fonts/';
        $this->MaybeCreateFolder($fontFolder,false);
        return $fontFolder;
    }

    public function GetFontURL(){

        return $this->fileIntegration->GetUploadURL().'/'.$this->Loader->Prefix.'/fonts/';
    }


    private function GetTempFolderRootPath()
    {
        $tempFolder=$this->GetRootFolderPath().'temp/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }

    public function GetFileUploadPath()
    {
        $path=$this->GetRootFolderPath().'FileUploads/';
        $this->MaybeCreateFolder($path,true);
        return $path;
    }

    public function MaybeCreateFolder($directory,$secure=false)
    {
        if(!is_dir($directory))
            if(!mkdir($directory,0777,true))
                throw new Exception('Could not create folder '.$this->_rootPath);
            else{
                if($secure)
                {
                    @file_put_contents( $directory . '.htaccess', 'deny from all' );
                    @touch( $directory . 'index.php' );
                }
            }


    }


    public function UploadFile($name)
    {
        $fileUploadDir=$this->GetFileUploadPath();
        $value=null;
        if(isset($_FILES[$name]))
            $value=$_FILES[$name];

        if($value==null)
            throw new Exception('File could not be uploaded');

        $wp_filetype=$this->fileIntegration->CheckFileType( $value['tmp_name'], $value['name'], false );
        $ext = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
        $type = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];

        if ( ( ! $type || !$ext ) && ! current_user_can( 'unfiltered_upload' ) ) {
            throw new Exception('Invalid File Type');
        }


        $ext=pathinfo($value["name"], PATHINFO_EXTENSION);
        $originalFileName=$value['name'];

        $fileName=uniqid("",true).".".pathinfo($value["name"], PATHINFO_EXTENSION);
        $fileName=$this->fileIntegration->GetUniqueFileName($fileUploadDir,$fileName);


        if(@move_uploaded_file( $value['tmp_name'], $fileUploadDir.$fileName )===false)
        {
            throw new Exception('Could not upload file');
        }

        return $fileName;
    }

    public function MaybeMoveToPermanentPath($Path)
    {
        $folder=$this->GetOrderFolderRootPath();

        if(\strpos($Path,$this->GetTempFolderRootPath()) !==0)
            return;

        $fileName=uniqid("",true).'.'.pathinfo($Path, PATHINFO_EXTENSION);;
        $fileName=wp_unique_filename($folder,$fileName);

        if(@copy( $Path, $folder.$fileName )===false)
        {
            throw new Exception('Could not upload file');
        }

        return $folder.$fileName;
    }

    public function MoveImageData($imageData)
    {
        $fileIntegration=new FileIntegration($this->Loader);
        $tempDir=$this->GetImageFolderRootPath();
        $fileName=uniqid("",true).'.png';
        $fileName=$fileIntegration->GetUniqueFileName($tempDir,$fileName);

        file_put_contents( $tempDir.$fileName, $imageData );
        return $fileName;


    }
    public function MoveSignature($signature)
    {
        $fileIntegration=new FileIntegration($this->Loader);
        $tempDir=$this->GetSignatureFolderRootPath();
        $fileName=uniqid("",true).'.png';
        $fileName=$fileIntegration->GetUniqueFileName($tempDir,$fileName);



        $data = base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $signature ) );
        $save_signature = file_put_contents( $tempDir.$fileName, $data );

        return $fileName;
    }

    /**
     * @param $fileId
     * @param $fileReference
     * @return FileData
     */
    public function GetFileData($fileId,$fileReference)
    {
        $dbManager=new DBManager();
        $result=$dbManager->GetResult('select physical_name,file_type,name,mime_type from '.$this->Loader->RECORDS_FILES.' where file_sequence_id=%d and file_reference=%s',$fileId,$fileReference);

        if($result==null)
            return null;

        $basePath='';
        $name=$result->name;
        switch($result->file_type)
        {
            case 'signature':
                $basePath=$this->GetSignatureFolderRootPath();
                $name='Signature.png';
                break;
            case 'file':
                $basePath=$this->GetFileUploadPath();
                break;
            case 'map':
                $basePath=$this->GetMapsFolderRootPath();
                break;
            case 'image':
                $basePath=$this->GetImageFolderRootPath();
                break;

        }

        return new FileData($result->file_type,$name,$basePath.$result->physical_name,$result->mime_type);


    }

    public function GetFileMimeType($filePath)
    {
        $mtype = false;
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $filePath);
            finfo_close($finfo);
        } elseif (function_exists('mime_content_type')) {
            $mtype = mime_content_type($filePath);
        }
        return $mtype;
    }

    public function GetDownloadLink($fileId,$fileReference)
    {
        $id=$fileId.'__'.$fileReference;
        return IntegrationURL::AjaxURL().'?action='.$this->Loader->Prefix.'_getpublicfileupload&ref='.\urlencode($id);
    }

    public function DeleteFile($fileType, $PhysicalName)
    {
        $path='';
        switch ($fileType)
        {
            case 'map':
                $path=$this->GetMapsFolderRootPath();
                break;
            case 'signature':
                $path=$this->GetSignatureFolderRootPath();
                break;
            case 'file':
                $path=$this->GetFileUploadPath();
                break;
        }

        $realPath=$path.$PhysicalName;
        if(\file_exists($realPath))
            \unlink($realPath);
    }

    public function GetTempPath()
    {
        $tempFolder=$this->GetRootFolderPath().'temp/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }

    /**
     * @param $path
     * @return UploadFileToMediaResult
     */
    public function UploadFileToMedia($path)
    {
        if(!\file_exists($path))
            return null;
        $fileName=\pathinfo($path,\PATHINFO_BASENAME);
        $result=\wp_upload_bits($fileName,null,\file_get_contents($path));
        if(isset($result['error'])&&$result['error']!=false)
        {
            throw new FriendlyException('An error occurred while uploading image'.$path.' message:'.$result['error']);
        }

        $attachment_id = wp_insert_attachment(null, $result['file'] );

        $mediaResult=new UploadFileToMediaResult();
        $mediaResult->URLId=$attachment_id;
        $mediaResult->URL=$result['url'];

        return $mediaResult;
    }

    public function GetAssetFolderPath($formId)
    {
        if(!is_numeric($formId))
            return '';

        $folder= $this->GetRootFolderPath().'assets/'.$formId.'/';
        $this->MaybeCreateFolder($folder,true);
        return $folder;
    }

    public function SaveAsset($formId, $fileName,$data)
    {
        $path=$this->GetAssetFolderPath($formId);
        $fileName=sanitize_file_name($fileName);

        if(file_put_contents($path.$fileName,$data))
            return $fileName;
        return null;

    }

    public function GetAssetString($formId,$fileName)
    {
        $path=$this->GetAssetFolderPath($formId);
        $fileName=sanitize_file_name($fileName);

        if(file_exists($path.$fileName))
            return file_get_contents($path.$fileName);
        return null;
    }

    public function MaybeDeleteAsset($formId, $fileName)
    {
        $path=$this->GetAssetFolderPath($formId);
        $fileName=sanitize_file_name($fileName);

        if(file_exists($path.$fileName))
            return unlink($path.$fileName);
        return false;
    }
}

class UploadFileToMediaResult{
    public $URL;
    public $URLId;
}

class FileData{
    public $Name;
    public $Path;
    public $Mime;
    public $FileType;

    public function __construct($fileType,$name,$path,$mime)
    {
        $this->FileType=$fileType;
        $this->Name=$name;
        $this->Path=$path;
        $this->Mime=$mime;
    }


}