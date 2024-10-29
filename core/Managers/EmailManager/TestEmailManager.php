<?php


namespace rednaoeasycalculationforms\core\Managers\EmailManager;


use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\SlateGenerator;

class TestEmailManager extends EmailManager
{


    public function Initialize($model, $email)
    {
        parent::Initialize($model, $email);
        $this->SlateGenerator->SetIsTest();
    }

    public function ShouldEmailBeSend()
    {
        return true;
    }


}