<?php


namespace rednaoeasycalculationforms\core\Managers\EntrySaver;


class EasyCalculationEntry
{
    public $UserId;
    public $Sequence;
    public $FormattedSequence;
    public $UnixDate;
    public $Data;
    public $Total;
    public $Status;
    public $EntryId;
    public $FormId;
    public $UserName;
    public $UserEmail;
    public $ReferenceId;
    /** @var EasyCalculationMeta[] */
    public $Meta;
    public $EditNonce;

    public function __construct()
    {
        $this->Meta=[];
    }


}