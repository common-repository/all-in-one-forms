<?php


namespace rednaoeasycalculationforms\core\Exception;


use Exception;
use Throwable;

class FriendlyException extends Exception
{
    public $FriendlyMessage;
    public $Details;
    public function __construct($friendlyMessage,$details='')
    {
        $this->FriendlyMessage=$friendlyMessage;

        if($details=='')
            $this->Details=$friendlyMessage;
        else
            $this->Details=$details;

        parent::__construct($this->Details);
    }

    public function GetFriendlyException(){
        return $this->FriendlyMessage;
    }



}