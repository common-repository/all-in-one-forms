<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter;


use stdClass;

class StringQueryFormatter extends QueryFormatterBase
{
    public function FormatRow($row){
        $formBuilder=$this->GetFormBuilder();
        $entry=new stdClass();
        $entry->Fields=\json_decode($row->data);
        $formBuilder->SetEntry($entry);

        $fields=$formBuilder->ContainerManager->GetFields(false,false,false);

        $rowToReturn=array();
        foreach($fields as $currentField){
            $field=$currentField->ToText();
        }



        $rowToReturn=new stdClass();
        $rowToReturn->UserId=$row->user_id;
        $rowToReturn->Sequence=$row->formatted_sequence;
        $rowToReturn->UnixDate=\strtotime($row->date);
        $rowToReturn->Data=\json_decode($row->data);
        $rowToReturn->Total=\floatval($row->total);
        $rowToReturn->Status=$row->status;
        $rowToReturn->EntryId=$row->entry_id;
        $rowToReturn->FormId=$row->form_id;
        $rowToReturn->UserName='';
        $rowToReturn->UserEmail='';
        $rowToReturn->ReferenceId=$row->reference_id;

        $userInfo=$this->UserIntegration->GetUserInfoById($rowToReturn->UserId);
        if($userInfo!=null)
        {
            $rowToReturn->UserName = $userInfo->Name;
            $rowToReturn->UserEmail=$userInfo->Email;
        }



        return $rowToReturn;
    }
}