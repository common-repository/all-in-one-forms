<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use Exception;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\Managers\FormManager\Fields\FBGroupPanel;

class CSVColumnFactory
{
    /**
     * @param $field FBFieldBase
     */
    public static function GetCSVColumnByField($loader,$field)
    {
        switch ($field->Options->Type)
        {
            case 'text':
            case 'numeric':
            case 'textarea':
            case 'email':
            case 'datepicker':
            case 'ludate':
            case 'switch':
            case 'masked':
            case 'slider':
            case 'website':
            case 'lutextbox':
            case 'buttoncounter':
            case 'luqrscanner':
                return [new BasicStringCSVColumn($loader,$field->Options->Label,['Value'],$field)];

            case 'dropdown':
            case 'checkbox':
            case 'radio':
            case 'buttonselection':
            case 'imagepicker':
            case 'colorswatcher':
            case 'ludropdown':
            case 'luradio':
            case 'lucheckbox':
            case 'wc_dropdown':
                return [new MultipleOptionsCSVColumn($loader,$field->Options->Label,['SelectedValues'],$field)];
            case 'name':
                if($field->Options->Format=='single')
                    return [new BasicStringCSVColumn($loader,$field->Options->FirstNameLabel,['Value','Name'],$field)];
                else return [
                    new BasicStringCSVColumn($loader,$field->Options->FirstNameLabel,['Value','FirstName'],$field),
                    new BasicStringCSVColumn($loader,$field->Options->LastNameLabel,['Value','LastName'],$field)
                ];

            case 'address':
                $columns=[];
                $columns[]=new BasicStringCSVColumn($loader,$field->Options->Address1Label,['Value','Address1'],$field);

                if($field->Options->ShowAddress2)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->Address2Label,['Value','Address2'],$field);

                if($field->Options->ShowCity)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->CityLabel,['Value','City'],$field);

                if($field->Options->ShowState)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->StateLabel,['Value','State'],$field);

                if($field->Options->ShowZip)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->ZipLabel,['Value','Zip'],$field);

                if($field->Options->ShowCountry)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->CountryLabel,['Value','Country'],$field);

                return $columns;

            case 'colorpicker':
                return [new BasicStringCSVColumn($loader,$field->Options->Label,['Value'],$field)];
            case 'signature':
                return [new LinkCSVColumn($loader,$field->Options->Label,null,$field)];
            case 'googlemaps':
                $columns=array();
                $columns[]=new BasicStringCSVColumn($loader,$field->Options->Address1Label,['Value','Address1'],$field);

                if($field->Options->ShowAddress2)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->Address2Label,['Value','Address2'],$field);

                if($field->Options->ShowCity)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->CityLabel,['Value','City'],$field);

                if($field->Options->ShowState)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->StateLabel,['Value','State'],$field);

                if($field->Options->ShowZip)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->ZipLabel,['Value','Zip'],$field);

                if($field->Options->ShowCountry)
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->CountryLabel,['Value','CountryLong'],$field);

                return $columns;
            case 'textualimage':
                return [new TextualImageCSVColumn($loader,$field->Options->Label,null,$field)];
            case 'list':
                return [new ListColumn($loader,$field->Options->Label,null,$field)];
            case 'fileupload':
                return [new FileUploadColumn($loader,$field->Options->Label,null,$field)];
            case 'grouppanel':
                return CSVColumnFactory::GetGroupPanelColumns($loader,$field);
            case 'repeater':
                return [new RepeaterColumn($loader,$field->Options->Label,null,$field)];
            case 'survey':
                $columns=array();
                foreach($field->Options->Rows as $currentRow)
                {
                    $currentColumn=new BasicStringCSVColumn($loader,$currentRow->Label,null,$field);
                    $currentColumn->SetValueRetriever(function ($source) use($currentRow){
                        $selectedValue=ArrayUtils::Filter($source->SelectedValues,function ($item) use($currentRow){
                            return $item->Row->Id==$currentRow->Id;
                        });

                        if(count($selectedValue)=='')
                            return '';

                        $labels= ArrayUtils::Map($selectedValue,function ($item){return $item->Column->Label;});
                        return implode(', ',$labels);
                    });
                    $columns[]=$currentColumn;
                }

                return $columns;

            case 'chained':
                $columns=[];
                for($i=0;$i<count($field->Options->Columns);$i++)
                {
                    $columns[]=new BasicStringCSVColumn($loader,$field->Options->Columns[$i],['Value',$i],$field);
                }
                return $columns;



        }
        return [];
    }

    /**
     * @param $loader
     * @param FBGroupPanel $field
     */
    private static function GetGroupPanelColumns($loader, FBFieldBase $field)
    {
        $columns=array();
        $fieldList=$field->ContainerManager->GetFields(false,false,true);
        /** @var FBFieldBase $currentField */
        foreach($fieldList as $currentField)
        {
            if(!$currentField->GetStoresInformation())
                continue;

            $columns=\array_merge($columns,CSVColumnFactory::GetCSVColumnByField($loader,$currentField));

        }

        foreach($columns as $currentColumn)
        {
            $currentColumn->SetParent($field);
        }

        return $columns;

    }

}