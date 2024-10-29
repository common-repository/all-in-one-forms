<?php

namespace rednaoeasycalculationforms\Managers\FontManager;


use League\Uri\Exception;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;

class FontManager
{
    public $fontList=null;
    public function InsertFont($options)
    {
        switch ($options->Type)
        {
            case 'File':
                return $this->InsertTTF($options);
            case 'URL':
                return $this->InsertURLFont($options);
            case 'Inline':
                return $this->InsertInlineFont($options);
        }
    }

    public function InsertInlineFont($options)
    {
        $name=sanitize_text_field($options->Name);

        if(trim($name)=='')
            throw new Exception('Invalid font name');

        $data=new \stdClass();
        $data->Type='Inline';
        $this->SaveFontData($name,$data);
        return [
            'FontName'=>$name,
            'FontData'=>$data
        ];
    }

    public function InsertURLFont($options)
    {
        $data=new \stdClass();
        $data->Type='URL';
        $name=sanitize_text_field($options->Name);

        if(trim($name)=='')
            throw new Exception('Invalid font name');
        if(!isset($options->R)||!filter_var($options->R,FILTER_VALIDATE_URL))
            throw new FriendlyException('Invalid font url');


        $data->R=$options->R;
        if(isset($options->B))
        {
            if(!filter_var($options->B,FILTER_VALIDATE_URL))
                throw new FriendlyException('Invalid bold font url');

            $data->B=$options->B;
        }

        if(isset($options->I))
        {
            if(!filter_var($options->I,FILTER_VALIDATE_URL))
                throw new FriendlyException('Invalid bold font url');

            $data->I=$options->I;
        }

        if(isset($options->BI))
        {
            if(!filter_var($options->BI,FILTER_VALIDATE_URL))
                throw new FriendlyException('Invalid bold font url');

            $data->BI=$options->BI;
        }

        $this->SaveFontData($name,$data);
        return [
            'FontName'=>$name,
            'FontData'=>$data
        ];
    }


    public function InsertTTF($options)
    {
        $fontName=sanitize_file_name(pathinfo($options->Name,PATHINFO_FILENAME));

        if($fontName=='')
            throw new FriendlyException('Font name is empty');

        if(!isset($_FILES['Regular'])||!$this->FontFilesAreValid())
            throw new FriendlyException('Invalid font file(s) uploaded');

        $fontData=new \stdClass();
        $fontData->Type='File';

        $fileManager=new FileManager(AllInOneForms()->GetLoader());
        $dirPath=$fileManager->GetFontPath().$fontName;

        if(is_dir($dirPath))
        {
            $files = glob($dirPath . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete individual file
                }
            }
        }else{
            mkdir($dirPath);
        }

        if(isset($_FILES['Regular'])) {
            $fontData->R=true;
            move_uploaded_file($_FILES['Regular']['tmp_name'], $dirPath . '/Regular.ttf');
        }

        if(isset($_FILES['Bold'])) {
            $fontData->B=true;
            move_uploaded_file($_FILES['Bold']['tmp_name'], $dirPath . '/Bold.ttf');
        }

        if(isset($_FILES['Italic'])) {
            $fontData->I=true;
            move_uploaded_file($_FILES['Italic']['tmp_name'], $dirPath . '/Italic.ttf');
        }

        if(isset($_FILES['BoldItalic'])) {
            $fontData->BI=true;
            move_uploaded_file($_FILES['BoldItalic']['tmp_name'], $dirPath . '/BoldItalic.ttf');
        }

        $this->SaveFontData($fontName,$fontData);
        return [
            'FontName'=>$fontName,
            'FontData'=>$fontData
        ];
    }

    private function FontFilesAreValid()
    {
        if(!isset($_FILES['Regular']))
            return false;

        $filesToCheck=['Regular','Bold','Italic','BoldItalic'];

        foreach($filesToCheck as $currentFile)
        {
            if(isset($_FILES[$currentFile]))
            {
                $file=$_FILES[$currentFile];
                if($file['error']!=0)
                    return false;
                if(pathinfo($file['name'],PATHINFO_EXTENSION)!='ttf')
                    return false;
            }
        }

        return true;
    }

    public function RemoveFont($fontName)
    {
        $fontData=get_option('aio_fonts',[]);
        if(isset($fontData[$fontName]))
        {
            $fileManager=new FileManager(AllInOneForms()->GetLoader());
            $fontPath=$fileManager->GetFontPath().sanitize_file_name($fontName);

            if(is_dir($fontPath))
            {
                $files = glob($fontPath . '/*');

                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file); // Delete individual file
                    }
                }
                rmdir($fontPath);
            }

            unset($fontData[$fontName]);
            update_option('aio_fonts',$fontData);
        }
    }

    private function SaveFontData($fontName, $newFontData)
    {
        $fontData=get_option('aio_fonts',[]);
        $fontData[$fontName]=$newFontData;

        update_option('aio_fonts',$fontData);

    }

    public function GetFonts()
    {
        if($this->fontList===null)
            $this->fontList=get_option('aio_fonts',[]);

        return $this->fontList;
    }

    public function GetFontFace($fontName)
    {
        $fonts=$this->GetFonts();
        if(!isset($fonts[$fontName]))
            return '';

        $fontFace='';
        $fontData=$fonts[$fontName];
        switch ($fontData->Type)
        {
            case 'Inline':
                return '';
            case 'File':
                $fontName=sanitize_file_name($fontName);
                $fileManager=new FileManager(AllInOneForms()->GetLoader());
                $dirPath=$fileManager->GetFontURL().$fontName.'/';

                $fontFace=$this->CreateFontFace($fontName,$dirPath.'Regular.ttf');

                if(isset($fontData->B))
                    $fontFace.=$this->CreateFontFace($fontName,$dirPath.'Bold.ttf',true);
                if(isset($fontData->I))
                    $fontFace.=$this->CreateFontFace($fontName,$dirPath.'Italic.ttf',false,true);
                if(isset($fontData->BI))
                    $fontFace.=$this->CreateFontFace($fontName,$dirPath.'BoldItalic.ttf',true,true);
                break;
            case 'URL':
                if(isset($fontData->R))
                    $fontFace.=$this->CreateFontFace($fontName,$fontData->R);
                if(isset($fontData->B))
                    $fontFace.=$this->CreateFontFace($fontName,$fontData->B,true);
                if(isset($fontData->I))
                    $fontFace.=$this->CreateFontFace($fontName,$fontData->I,false,true);
                if(isset($fontData->BI))
                    $fontFace.=$this->CreateFontFace($fontName,$fontData->BI,true,true);


        }

        return $fontFace;

    }

    private function CreateFontFace($fontName,$url,$isBold=false,$isItalic=false){
        $text="@font-face { font-family:'$fontName'; src: url('$url') format('truetype');";

        if($isBold)
            $text.=" font-weight: bold; ";

        if($isItalic)
            $text.=" font-style: italic; ";

        $text.="}\n";

        return $text;
    }

}