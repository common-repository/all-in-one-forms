<?php


namespace rednaoeasycalculationforms\Managers\QueryManager;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\Integration\DateIntegration;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class WhereStatement
{
    static $WhereCount=0;
    public $FieldId;
    public $Comparator;
    public $Value;
    public $PathId;
    /** @var WhereGroup */
    public $WhereGroup;
    public $TableName;
    public $TableId;
    public $SubType='';
    public $Column='';
    public $ValueSubType='';
    public $Type='';
    public function __construct($whereGroup,$fieldId,$comparator,$value,$tableName,$tableId,$fieldPath,$subType='',$valueSubType='',$type='')
    {
        $this->Type=$type;
        $this->TableName=$tableName;
        $this->TableId=$tableId;
        $this->WhereGroup=$whereGroup;
        $this->FieldId=$fieldId;
        $this->Comparator=$comparator;
        $this->Value=$value;
        $this->PathId=$fieldPath;
        $this->ValueSubType=$valueSubType;
        if($this->FieldId=='_startDate'||$this->FieldId=='_endDate')
        {
            $this->SubType='Date';
            $this->Column='date';
        }else{
            $this->SubType=$subType;
        }
    }

    public function GenerateWhereStatement(){
        $statement='';
        $dateIntegration=new DateIntegration($this->WhereGroup->QueryManager->Loader);
        $dbmanager=new DBManager();
        if($this->FieldId=='_startDate'||$this->FieldId=='_endDate')
        {
            return 'record.date '.$this->Comparator.' '. $dbmanager->Escape($dateIntegration->GetTimezonedDateFromUTCDate(date('c', $this->Value)) );
        }

        if($this->FieldId=='_entryId')
        {
            return 'record.entry_id in ('.\implode($this->Value).')';
        }
        return '';
    }

}