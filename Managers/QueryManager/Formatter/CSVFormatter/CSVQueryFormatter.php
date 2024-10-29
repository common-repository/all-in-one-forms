<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter;


use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns\BasicStringCSVColumn;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns\CSVColumn;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns\CSVColumnFactory;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\QueryFormatterBase;
use stdClass;

class CSVQueryFormatter extends QueryFormatterBase
{
    /** @var CSVColumn[] */
    public $Columns=null;

    public function FormatRow($row)
    {

        $formBuilder=$this->GetFormBuilder();
        $entry=new stdClass();
        $entry->Data=$row->data;
        $formBuilder->SetEntry($entry->Data);

        $columns=$this->GetColumns();
        $rows=[];

        $data=[];
        $additionalRows=[];
        foreach($columns as $currentColumn)
        {


            $currentColumn->Field=$formBuilder->GetFieldById($currentColumn->Field->Options->Id);
            $formattedResult=$currentColumn->Format($row);
            if($currentColumn->CanAddMultipleRows()) {

                $index=count($data);
                for($i=1;$i<count($formattedResult);$i++) {
                    $additionalRowColumns = $formattedResult[$i];
                    if (!isset($additionalRows[$i]))
                        $additionalRows[$i] = [];
                    for ($t = 0; $t < count($additionalRowColumns); $t++) {
                        $additionalRows[$i][$index + $t] = $additionalRowColumns[$t];
                    }


                }
                if (count($formattedResult) > 0)
                    $data = array_merge($data, $formattedResult[0]);
            }
            else
                $data[]=$formattedResult;
        }

        $rows=[$data];

        foreach($additionalRows as $currentRow)
        {
            $newRow=[];
            for($i=0;$i<count($data);$i++)
            {
                if(isset($currentRow[$i]))
                    $newRow[]=$currentRow[$i];
                else
                    $newRow[]='';
            }
            $rows[]=$newRow;
        }
        return $rows;
    }

    public function CanAddMultipleRows()
    {
        return true;
    }

    /**
     * @return CSVColumn[]
     */
    private function GetColumns()
    {
        if($this->Columns==null)
        {
            $this->Columns=[];
            $this->Columns[]=new BasicStringCSVColumn($this->QueryManager->Loader,'Entry #',['sequence'],null);
            $this->Columns[]=new BasicStringCSVColumn($this->QueryManager->Loader,'Date',['date'],null);
            $this->Columns[]=new BasicStringCSVColumn($this->QueryManager->Loader,'Status',['status'],null);

            $formBuilder=$this->GetFormBuilder();
            $fields=$formBuilder->ContainerManager->GetFields(false,false,false);
            foreach($fields as $currentField)
            {
                $this->Columns=\array_merge($this->Columns,CSVColumnFactory::GetCSVColumnByField($this->QueryManager->Loader,$currentField));
            }

            $this->Columns[]=new BasicStringCSVColumn($this->QueryManager->Loader,'Total',['total'],null);
        }

        return $this->Columns;
    }

    public function PostProcess($itemsToReturn)
    {
        $headers=[];
        foreach($this->Columns as $currentColumn)
        {
            $headers=\array_merge($headers,$currentColumn->GetHeaders());
        }
        $arrayToReturn=array(
            'Columns'=>$headers,
            'Data'=>$itemsToReturn
        );
        return (object)$arrayToReturn;
    }


}