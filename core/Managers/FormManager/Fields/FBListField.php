<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;

class FBListField extends FBFieldBase
{

    public function GetItems()
    {
        return [
            'Columns'=>$this->GetOptionValue('Columns',array()),
            'Rows'=>$this->GetEntryValue('Value',array())
        ];
    }

    public function GetHTMLTemplate($context = null)
    {
        return 'core/Managers/FormManager/Fields/FBListField.twig';
    }


    public function InternalToText()
    {
        $text='';
        $rowValues=$this->GetEntryValue('Value',array());

        foreach($rowValues as $currentRow)
        {
            $columnsOfRow=[];
            foreach($currentRow->Columns as $currentcolumn)
            {
                $columnsOfRow[]=$currentcolumn;
            }

            if($text!='')
                $text.=' | ';
            $text.=\implode(', ',$columnsOfRow);
        }

        return $text;

    }



    public function GetLineItems()
    {
        $lineItems= parent::GetLineItems()[0];
        $lineItemList=array();
        if(count($this->Options->Columns)>1)
        {
            foreach($this->Entry->Value as $listItem)
            {
                if(!isset($listItem->Columns))
                    continue;

                for($i=0;$i<count($this->Options->Columns);$i++)
                {
                    if(count($listItem->Columns)<=$i)
                        continue;

                    $newItem=$lineItems->CloneItem();
                    $newItem->UnitPrice=0;
                    $newItem->Value=$listItem->Columns[$i];
                    $newItem->SubType=substr($this->Options->Columns[$i]->Name,0,200);
                    $newItem->UnitPrice=$listItem->total->Price;
                    $lineItemList[]=$newItem;
                }

            }
        }else{
            foreach ($this->Entry->Value as $listItem)
            {
                if(!isset($listItem->Columns)||count($listItem->Columns)==0)
                    continue;

                $newItem=$lineItems->CloneItem();
                $newItem->UnitPrice=0;
                $newItem->Value=$listItem->Columns[0];
                $newItem->SubType='';
                $newItem->ExValue1=substr($this->Options->Columns[0]->Name,0,200);
                $newItem->UnitPrice=$listItem->total->Price;
                $lineItemList[]=$newItem;


            }
        }


        return $lineItemList;
    }



}