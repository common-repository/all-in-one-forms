<?php


namespace rednaoeasycalculationforms\core\Managers\EntrySaver\ConfirmationActions;


class MessageConfirmationAction extends ConfirmationActionBase
{
    public $Content;
    public $Title;
    public $ButtonText;
    public $IconType;
    public $RedirectURL;

    static $ICON_TYPE_SUCCESS='Success';
    static $ICON_TYPE_ERROR='Error';
    static $ICON_TYPE_WARNING='Warning';

    public function __construct($content,$title,$buttonText,$iconType='Success',$redirectURL='')
    {
        $this->Type='message';
        $this->Content=$content;
        $this->Title=$title;
        $this->ButtonText=$buttonText;
        $this->IconType=$iconType;
        $this->RedirectURL=$redirectURL;
    }


}

