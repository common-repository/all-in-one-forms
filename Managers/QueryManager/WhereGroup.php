<?php


namespace rednaoeasycalculationforms\Managers\QueryManager;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\Integration\DateIntegration;

class WhereGroup
{
    /** @var WhereStatement[] */
    public $Statements;
    /** @var $QueryManager */
    public $QueryManager;


    public function __construct($queryManager)
    {
        $this->Entries=array();
        $this->QueryManager=$queryManager;
        $this->Statements=[];
    }

    public function AddWhereStatement($fieldId,$comparator,$value,$tableName='',$tableId='',$fieldPath='',$subType='',$valueSubType='',$type='')
    {
        $whereStatement=new WhereStatement($this,$fieldId,$comparator,$value,$tableName,$tableId,$fieldPath,$subType,$valueSubType,$type);
        $this->Statements[]=$whereStatement;
        return $this;
    }


    public function AddStartDate($startDate)
    {
        $this->AddWhereStatement('_startDate','GreaterOrEqualThan',$startDate,'','ROOT');
        return $this;
    }

    public function AddEndDate($endDate)
    {
        $this->AddWhereStatement('_endDate','LessThan',$endDate,'','ROOT');
        return $this;
    }

    public function GenerateWhereGroup(){
        $whereStatements=array();

        foreach($this->Statements as $statement)
        {
            $whereStatements[]=$statement->GenerateWhereStatement();
        }

        if(count($whereStatements)>0)
        {
            return '('.\implode(' and ',$whereStatements).')';
        }


        return '';




    }

    public function AddEntryId($entryIds)
    {
        if(!\is_array($entryIds))
            $entryIds=[$entryIds];

        $this->AddWhereStatement('_entryId','Contains',$entryIds);

        return $this;
    }


}