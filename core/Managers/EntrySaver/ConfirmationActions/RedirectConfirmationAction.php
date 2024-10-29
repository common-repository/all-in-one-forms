<?php


namespace rednaoeasycalculationforms\core\Managers\EntrySaver\ConfirmationActions;


class RedirectConfirmationAction extends ConfirmationActionBase
{
    public $URL;

    public function __construct($url)
    {
        $this->Type='redirect';
        $this->URL=$url;
    }

}