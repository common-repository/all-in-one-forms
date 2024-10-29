<?php


namespace rednaoeasycalculationforms\core\Managers\RestrictionManager;


use Exception;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;

class UniqueRestriction
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    /**
     * @param $field FBFieldBase
     * @param $numberOfTimes
     */
    public function ValidateRestriction($field,$numberOfTimes)
    {
        $lineItems=$field->GetLineItems();
        $query='';
        $db=new DBManager();

        foreach($lineItems as $currentLineItem)
        {

            if($currentLineItem->Value!=null)
                $query=$this->AddWhere($query,'value='.$db->Escape($currentLineItem->Value));

            if($currentLineItem->ExValue1!=null)
                $query=$this->AddWhere($query,'exvalue1='.$db->Escape($currentLineItem->ExValue1));

            if($currentLineItem->ExValue2!=null)
                $query=$this->AddWhere($query,'exvalue2='.$db->Escape($currentLineItem->ExValue2));

            if($currentLineItem->ExValue3!=null)
                $query=$this->AddWhere($query,'exvalue3='.$db->Escape($currentLineItem->ExValue3));

            if($currentLineItem->ExValue4!=null)
                $query=$this->AddWhere($query,'exvalue4='.$db->Escape($currentLineItem->ExValue4));

            if($currentLineItem->ExValue5!=null)
                $query=$this->AddWhere($query,'exvalue5='.$db->Escape($currentLineItem->ExValue5));

            if($currentLineItem->ExValue6!=null)
                $query=$this->AddWhere($query,'exvalue6='.$db->Escape($currentLineItem->ExValue6));


            if($currentLineItem->NumericValue!=null)
                $query=$this->AddWhere($query,'numericvalue='.$db->Escape($currentLineItem->NumericValue));

            if($currentLineItem->NumericValue2!=null)
                $query=$this->AddWhere($query,'numericvalue2='.$db->Escape($currentLineItem->NumericValue2));

            if($currentLineItem->DateValue!=null)
                $query=$this->AddWhere($query,'datevalue='.$db->Escape($currentLineItem->DateValue));

            if($currentLineItem->DateValue2!=null)
                $query=$this->AddWhere($query,'datevalue2='.$db->Escape($currentLineItem->DateValue2));

            $count=$db->GetVar('select count(*) from '.$this->Loader->RECORDS_DETAIL.' where '.$query);
            if($count==null)
                throw new FriendlyException('An issue occurred while validating the restriction');

            if($count>=$numberOfTimes)
                return false;
        }

        return true;





    }

    public function AddWhere($query,$filter)
    {
        if($query!='')
            $query.=' and ';
        return $query.$filter;
    }
}