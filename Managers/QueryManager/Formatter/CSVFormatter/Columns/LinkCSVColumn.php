<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;

class LinkCSVColumn extends CSVColumn
{


    public function Format($dataSource)
    {
        $value=$this->GetValue($dataSource);

        if($value==null||!isset($value->FileId)||!isset($value->FileReference))
            return '';

        $fileManager=new FileManager($this->Loader);

        return $fileManager->GetDownloadLink($value->FileId,$value->FileReference);


    }
}