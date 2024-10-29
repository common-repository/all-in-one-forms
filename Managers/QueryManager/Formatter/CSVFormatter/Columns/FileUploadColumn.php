<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use rednaoeasycalculationforms\core\Integration\PageIntegration;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;

class FileUploadColumn extends CSVColumn
{


    public function Format($dataSource)
    {
        $value=$this->GetValue($dataSource,['Value']);

       if($value==null)
           return '';

       $fileManager=new FileManager($this->Loader);
       $links=ArrayUtils::Map($value,function ($item)use($fileManager){
           return $fileManager->GetDownloadLink($item->FileId,$item->FileReference);
       });

       return \join(', ',$links);


    }
}