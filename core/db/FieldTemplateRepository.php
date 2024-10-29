<?php

namespace rednaoeasycalculationforms\core\db;

use rednaoeasycalculationforms\core\db\core\RepositoryBase;

class FieldTemplateRepository extends RepositoryBase
{
    public function CreateTemplate($templateName,$options){

        $templateId=$this->DBManager->Insert($this->Loader->FIELD_TEMPLATE,[
           'name'=>$templateName,
            'options'=>json_encode($options)
        ]);

        return [
            "Id"=>$templateId,
            'Name'=>$templateName,
            'Options'=>$options
        ];
    }

    public function DeleteTemplate($templateId)
    {
        $this->DBManager->Delete($this->Loader->FIELD_TEMPLATE,['template_id'=>$templateId]);
    }

    public function GetList()
    {
        $results=$this->DBManager->GetResults("select template_id Id,name Name from ".$this->Loader->FIELD_TEMPLATE);
        return $results;
    }

    public function GetTemplateById($templateId)
    {
        $result= $this->DBManager->GetResult('select options Options from '.$this->Loader->FIELD_TEMPLATE.' where template_id=%d',$templateId);
        if($result!=null)
        {
            $result->Options=json_decode($result->Options);
        }

        return $result;
    }

}