<?php


namespace rednaoeasycalculationforms\core\Managers\ExportManager;


use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\core\Utils\FormSettingsIterator;
use rednaoeasycalculationforms\core\Utils\FormUtilities;
use rednaoeasycalculationforms\DTO\BuilderOptionsDTO;
use rednaoeasycalculationforms\Managers\FontManager\FontManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use ZipArchive;

class ExportManager
{
    public $zip;
    /** @var Loader */
    public $Loader;
    private $ZipPath;
    /** @var FileManager */
    private $FileManager;
    /**
     * ExportManager constructor.
     * @param $loader Loader
     */
    public function __construct($loader)
    {
        $this->zip=new ZipArchive();
        $this->FileManager=new FileManager($loader);


        $this->ZipPath=$this->FileManager->GetTempPath().'export.zip';
        $this->zip->open($this->ZipPath,\ZipArchive::CREATE|\ZipArchive::OVERWRITE);

    }

    /**
     * @param $builderOptions BuilderOptionsDTO
     */
    public function AddFormTemplate($builderOptions)
    {
        $formSettings=new FormSettingsIterator($builderOptions->FormBuilder);
        $formSettings->Iterate(function ($field){
            if($field->Type=='image')
            {
                if($field->Src!=null)
                {
                    $field->Src->URLId=$this->AddAttachment($field->Src->URLId);
                }

                $imageCondition=$this->GetConditionsByType($field,'Image');
                foreach($imageCondition as $currentImageCondition)
                {
                    if($currentImageCondition->Src!=null)
                        $currentImageCondition->Src->URLId=$this->AddAttachment($currentImageCondition->Src->URLId);
                }

            }

            if($field->Type=='buttonselection'||$field->Type=='popupselectorfield')
            {
                foreach($field->Options as $currentOption)
                {
                    if(Sanitizer::GetStringValueFromPath($currentOption,['Icon','ImageType'])=='image')
                    {
                        $currentOption->Icon->Ref->URLId=$this->AddAttachment($currentOption->Icon->Ref->URLId);
                    }
                }

            }
        });

        foreach($builderOptions->ServerOptions->ServerDependencies as $currentDependency)
        {
            if($currentDependency->Type=='Font')
                $this->MaybeAddFont($currentDependency->Id);
        }


        $this->zip->addFromString('Export.json',\json_encode($builderOptions));
    }

    public function CloseAndGetZipPath()
    {
        $this->zip->close();
        return $this->ZipPath;

    }

    public function Remove(){
        \unlink($this->ZipPath);
    }

    private function AddAttachment($URLId)
    {
        $file=\get_attached_file($URLId);
        if($file===false)
            return;

        $extension=\pathinfo($file,\PATHINFO_EXTENSION);

        if(!$this->zip->locateName('Attachments/'))
            $this->zip->addEmptyDir('Attachments');

        $this->zip->addFile($file,'Attachments/'.$URLId.'.'.$extension);
        return $URLId.'.'.$extension;

    }

    private function GetConditionsByType($field, $type)
    {
        $conditionToReturn=array();
        foreach($field->Conditions as $currentCondition)
            if($currentCondition->Type==$type)
                $conditionToReturn[]=$currentCondition;

        return $conditionToReturn;
    }

    private function MaybeAddFont($fontName)
    {
        $fontName=sanitize_file_name($fontName);
        $fontManager=new FontManager();
        $fontList=$fontManager->GetFonts();
        if(!isset($fontList[$fontName]))
            return;

        $fontData=$fontList[$fontName];
        if($fontData->Type!='File')
            return;

        if(!$this->zip->locateName('Fonts/'))
            $this->zip->addEmptyDir('Fonts');

        $this->zip->addEmptyDir('Fonts/'.$fontName);

        $fileManager=new FileManager(AllInOneForms()->GetLoader());
        $fontDir=$fileManager->GetFontPath().$fontName.'/';


        if(file_exists($fontDir.'Regular.ttf')) {
            $this->zip->addFile($fontDir . 'Regular.ttf', 'Fonts/' . $fontName . '/Regular.ttf');
        }

        if(file_exists($fontDir.'Bold.ttf')) {
            $this->zip->addFile($fontDir . 'Bold.ttf', 'Fonts/' . $fontName . '/Bold.ttf');
        }

        if(file_exists($fontDir.'Italic.ttf')) {
            $this->zip->addFile($fontDir . 'Italic.ttf', 'Fonts/' . $fontName . '/Italic.ttf');
        }

        if(file_exists($fontDir.'BoldItalic.ttf')) {
            $this->zip->addFile($fontDir . 'BoldItalic.ttf', 'Fonts/' . $fontName . '/BoldItalic.ttf');
        }


    }


}