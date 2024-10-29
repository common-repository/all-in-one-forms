<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use Exception;
use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Integration\PostIntegration;

class FBRecaptcha extends FBFieldBase
{
    public function GetLineItems()
    {
        return null;
    }

    public function PrepareForSerialization()
    {
        if($this->Entry==null)
        {
            throw new FriendlyException('Invalid Recaptcha Verification','A recaptcha value was not submitted');
        }

        $response=$this->Entry->Value->Response;
        $this->Entry=null;

        $settingsRepository=new SettingsRepository($this->Loader);
        $recaptchaSettings=$settingsRepository->GetRecaptchaSettings();

        $captcha=array();
        $captcha['response']=$response;
        $captcha["remoteip"]=$_SERVER['REMOTE_ADDR'];
        $captcha["secret"]=$recaptchaSettings->SecretKey;;
        $args=Array();
        $args['headers']=Array
        (
            'Content-Type'=>'application/x-www-form-urlencoded;',
            'Method'=>'Post'
        );
        $args['body']=$captcha;

        $postIntegration=new PostIntegration($this->Loader);
        if($recaptchaSettings->Type=='hcaptcha')
            $url='https://hcaptcha.com/siteverify';
        else
            $url='https://www.google.com/recaptcha/api/siteverify';
        $res=$postIntegration->RemotePost($url,$args);
        $response=json_decode($res["body"],true);


        if($recaptchaSettings->Type=='scoring')
        {
            if(\floatval($response['score'])<=\floatval($recaptchaSettings->MinimumScore))
            {
                throw new FriendlyException('Invalid Recaptcha Verification','The submission had an score of '.$response['score'].' and the minimum is '.$recaptchaSettings->MinimumScore);

            }
        }

        if($response["success"]===true)
            return null;



        if($response["error-codes"][0]=="invalid-input-secret")
            throw new FriendlyException('Invalid Recaptcha Verification','Invalid secret key');

        throw new FriendlyException('Invalid Recaptcha Verification','The recaptcha probably expired');
    }


}