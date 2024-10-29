<?php


namespace rednaoeasycalculationforms\core\Managers\ExportManager;


use Exception;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Utils\FormSettingsIterator;
use rednaoeasycalculationforms\DTO\BuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\FormBuilderOptionsDTO;
use rednaoeasycalculationforms\Managers\FontManager\FontManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use ZipArchive;

class ImportManager
{
    /** @var ZipArchive */
    public $zip;
    public $Loader;
    /** @var $FileManager */
    public $FileManager;
    public $TempFolderPath;
    /** @var FormBuilderOptionsDTO */
    public $FormOptions;
    /**
     * ImportManager constructor.
     * @param $loader Loader
     * @param $zipPath
     */
    public function __construct($loader)
    {
        $this->Loader=$loader;
        $this->FileManager=new FileManager($loader);
    }

    /**
     * @param $path
     * @return BuilderOptionsDTO
     * @throws \rednaoeasycalculationforms\core\Exception\FriendlyException
     */
    public function Import($path)
    {
        $this->zip=new ZipArchive();
        $this->TempFolderPath=$this->FileManager->GetTempPath(). $this->FileManager->GetSafeFileName($this->FileManager->GetTempPath(),'extracted_'.str_replace('.','',str_replace(' ','',\microtime()))).'/';
        if($this->zip->open($path)!==true)
            throw new Exception('Invalid zip file');

        $this->zip->extractTo($this->TempFolderPath);


        $content=\file_get_contents($this->TempFolderPath.'Export.json');
        if($content===false)
        {
            throw new Exception('The zip file does not have the required files');
        }

        $content=\json_decode($content);

        if($content===false||!isset($content->FormBuilder)||!isset($content->ServerOptions))
        {
            throw new Exception('The template.json is not valid');
        }

        $builderOptions=(new BuilderOptionsDTO())->Merge($content);

        $this->FormOptions=$builderOptions->FormBuilder;
        $builderOptions->Id=0;
        $repository=new FormRepository($this->Loader);

        $this->ImportAttachments();
        $this->ImportFonts();

        try
        {
            $repository->SaveForm($builderOptions, true);
        }catch(Exception $e)
        {
            throw $e;
        }

        return $builderOptions;

    }

    public function Remove(){
        \unlink($this->TempFolderPath);
    }

    private function ImportAttachments()
    {
        $formSettings=new FormSettingsIterator($this->FormOptions);
        $formSettings->Iterate(function ($field){
            if($field->Type=='image')
            {
                if($field->Src!=null)
                {
                    $newAttachment=$this->FileManager->UploadFileToMedia($this->TempFolderPath.'Attachments/'.$field->Src->URLId);
                    if($newAttachment==null)
                        return;

                    $field->Src->URLId=$newAttachment->URLId;
                    $field->Src->URL=$newAttachment->URL;
                }
                $imageCondition=$this->GetConditionsByType($field,'Image');
                foreach($imageCondition as $currentImageCondition)
                {
                    if($currentImageCondition->Src!=null)
                    {
                        $newAttachment=$this->FileManager->UploadFileToMedia($this->TempFolderPath.'Attachments/'.$currentImageCondition->Src->URLId);
                        if($newAttachment!=null)
                        {
                            $currentImageCondition->Src->URLId=$newAttachment->URLId;
                            $currentImageCondition->Src->URL=$newAttachment->URL;

                        }
                    }

                }
            }

            if($field->Type=='buttonselection'||$field->Type=='popupselectorfield')
            {
                foreach($field->Options as $currentOption)
                {
                    if(Sanitizer::GetStringValueFromPath($currentOption,['Icon','ImageType'])=='image')
                    {
                        $newAttachment=$this->FileManager->UploadFileToMedia($this->TempFolderPath.'Attachments/'.$currentOption->Icon->Ref->URLId);
                        if($newAttachment==null)
                            continue;

                        $currentOption->Icon->Ref->URLId=$newAttachment->URLId;
                        $currentOption->Icon->Ref->URL=$newAttachment->URL;
                    }
                }

            }
        });
    }


    private function GetConditionsByType($field, $type)
    {
        $conditionToReturn=array();
        foreach($field->Conditions as $currentCondition)
            if($currentCondition->Type==$type)
                $conditionToReturn[]=$currentCondition;

        return $conditionToReturn;
    }

    private function ImportFonts()
    {

        $fontsToImport=[];
        if($this->zip->locateName('Fonts/')!==false)
        {
            $fonts=$this->ListFiles('Fonts/');
            foreach($fonts as $currentFont)
            {
                $matches=null;
                preg_match_all('/Fonts\/([^\/]*)\/(.*).ttf$/',$currentFont,$matches,PREG_SET_ORDER);
                if($matches!=null&&count($matches)>0)
                {
                    $name=sanitize_file_name($matches[0][1]);
                    $type=$matches[0][2];

                    if(!isset($fontsToImport[$name]))
                        $fontsToImport[$name]=[];
                    switch ($type)
                    {
                        case 'Regular':
                            $fontsToImport[$name]['R']=true;
                            break;
                        case 'Bold':
                            $fontsToImport[$name]['B']=true;
                            break;
                        case 'Italic':
                            $fontsToImport[$name]['I']=true;
                            break;
                        case 'BoldItalic':
                            $fontsToImport[$name]['BI']=true;
                            break;
                    }
                }
            }
        }

        $fileManager=new FileManager(AllInOneForms()->GetLoader());
        $fontDir=$fileManager->GetFontPath();
        foreach($fontsToImport as $fontName=>$fontData)
        {
            if(is_dir($fontDir.$fontName))
                continue;
            mkdir($fontDir.$fontName);
            file_put_contents($fontDir.$fontName.'/Regular.ttf',$this->zip->getFromName('Fonts/'.$fontName.'/Regular.ttf'));

            if(isset($fontData['B']))
                file_put_contents($fontDir.$fontName.'/Bold.ttf',$this->zip->getFromName('Fonts/'.$fontName.'/Bold.ttf'));

            if(isset($fontData['I']))
                file_put_contents($fontDir.$fontName.'/Italic.ttf',$this->zip->getFromName('Fonts/'.$fontName.'/Italic.ttf'));

            if(isset($fontData['BI']))
                file_put_contents($fontDir.$fontName.'/BoldItalic.ttf',$this->zip->getFromName('Fonts/'.$fontName.'/BoldItalic.ttf'));

            $fontData['Type']='File';
            $fontList=get_option('aio_fonts',[]);
            $fontList[$fontName]=(Object)$fontData;
            update_option('aio_fonts',$fontList);

        }
    }


    public function ListFiles($path)
    {
        $files=[];
        for ($i = 0; $i < $this->zip->count(); $i++) {
            $fileInfo = $this->zip->statIndex($i);
            $filename = $fileInfo['name'];

            if (strpos($filename, $path) === 0) {
                $files[] = $filename;
            }
        }

        return $files;
    }

}


