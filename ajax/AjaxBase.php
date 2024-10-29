<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/1/2019
 * Time: 6:32 AM
 */

namespace rednaoeasycalculationforms\ajax;


use Exception;
use rednaoeasycalculationforms\ajax\AjaxSanitizer\AjaxSanitizerBase;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;
use rednaoeasycalculationforms\Utilities\ObjectSanitizer;

abstract class AjaxBase
{
    public $data=null;
    public $prefix;
    public $defaultNonce;
    private $privatePrefix;
    private $publicPrefix;
    private $ReturnType;
    private $returnResult;


    /** @var Loader */
    public $Loader;

    public function __construct($loader,$returnResult=false)
    {
        $this->returnResult=$returnResult;
        $this->defaultNonce=$this->GetDefaultNonce();
        $this->Loader=$loader;
        $this->prefix=$this->Loader->Prefix;
        $this->privatePrefix='wp_ajax_'.$this->Loader->Prefix.'_';
        $this->publicPrefix='wp_ajax_nopriv_'.$this->Loader->Prefix.'_';
        if(function_exists('wp_get_current_user'))
            $this->Initialize();
        else
            add_action('init',array($this,"Initialize"));


    }

    public function Initialize()
    {
        $this->RegisterHooks();
    }


    abstract function GetDefaultNonce();


    protected function RegisterPrivate($name,$methodName,$capability='administrator', $validateNonce=true,$nonceName=false,$monitorUnhandledErrors=true)
    {
        if($nonceName==false)
            $nonceName=$this->defaultNonce;

        $capabilityToUse=$capability;
        if(is_array($capability))
        {
            $currentCapability='administrator';
            foreach($capability as $currentCapability)
            {
                if(current_user_can($currentCapability))
                {
                    $capabilityToUse=$currentCapability;
                    break;
                }
            }

        }

        \add_action($this->privatePrefix.$name,function() use($methodName,$validateNonce,$nonceName,$capabilityToUse,$monitorUnhandledErrors){
            if($monitorUnhandledErrors==true)
                $this->MonitorUnhandledErrors();;
            if($validateNonce)
                $this->VerifyNonce($nonceName);
            if($capability!='')
                if(!\current_user_can($capability))
                    $this->SendErrorMessage('Forbidden');
            $this->$methodName();
            die();
        });
    }

    protected function RegisterPublic($name,$methodName, $validateNonce=true,$nonceName=false,$monitorUnhandledErrors=true)
    {
        if($nonceName==false)
            $nonceName=$this->defaultNonce;
        $this->RegisterPrivate($name,$methodName,'',$validateNonce,$nonceName);
        \add_action($this->publicPrefix.$name,function() use($methodName,$validateNonce,$nonceName,$monitorUnhandledErrors){

            if($monitorUnhandledErrors==true)
                $this->MonitorUnhandledErrors();;
            if($validateNonce)
                $this->VerifyNonce($nonceName);
            $this->$methodName();
            die();
        });
    }


    public function GetData(){
        if($this->data==null)
            if(isset($_POST['data']))
                $this->data=ObjectSanitizer::Sanitize(json_decode(stripslashes($_POST['data'])),(object)['Rows'=>'']);
            else
                if(isset($_GET['data']))
                    $this->data=ObjectSanitizer::Sanitize(json_decode(stripslashes($_GET['data'])),(object)['Rows'=>'']);
                else
                $this->SendErrorMessage('Invalid operation, data does not exists');
        return $this->data;
    }

    public function GetOptional($propertyName,$defaultValue='')
    {
        if(!isset($_POST['data'])&&!isset($_GET['data']))
            return $defaultValue;
        if(!property_exists($this->GetData(),$propertyName))
        {
            return $defaultValue;
        }

        return $this->data->$propertyName;
    }


    /**
     * @param $propertyName
     * @param $sanitizer AjaxSanitizerBase
     * @return mixed
     */
    public function GetRequired($propertyName,$sanitizer=null)
    {
        if(!property_exists($this->GetData(),$propertyName))
        {
            $this->SendErrorMessage('Property '.$propertyName. ' does not exists');
        }

        $data= $this->data->$propertyName;

        if($sanitizer==null)
            return $data;

        $data= $sanitizer->Sanitize($data);
        if($data===null)
            $this->SendErrorMessage('Property '.$propertyName. ' does not exists');

        return $data;
    }

    public function SendErrorMessage($message){

        echo json_encode(array(
            'success'=>false,
            'errorMessage'=>$message
        ));
        die();
    }

    /**
     * @param $e Exception|FriendlyException
     */
    public function SendException($e,$prefixLogDetails='',$debugModeEnabled=false){
        $friendlyMessage=__("An error occurred");

        if($e instanceof FriendlyException)
            $friendlyMessage=$e->GetFriendlyException();



        $message=$e->getMessage().' - '.$e->getTraceAsString();

        $detail='';
        if($debugModeEnabled)
            $detail=$message;
        LogManager::Log(LogManager::TYPE_ERROR,$message);
        echo json_encode(array(
            'success'=>false,
            'errorMessage'=>$friendlyMessage,
            'detail'=>$e->getMessage().' - '.$e->getTraceAsString()
        ));
        die();
    }

    public function SendSuccessMessage($data='')
    {
        if($this->returnResult)
        {
            return $data;
        }


        echo json_encode(array(
            'success'=>true,
            'result'=>$data
        ));
        die();


    }


    public function MonitorUnhandledErrors(){
        \add_filter('wp_die_ajax_handler',function () {
            $error =\error_get_last();
            $friendlyException=new FriendlyException('An unexpected error occurred ',$error['message']);
            $this->SendException($friendlyException,"An error occurred",$error['message']);
            die();
        });
    }


    abstract protected function RegisterHooks();

    private function VerifyNonce($nonceName)
    {
        $nonce='';
        if(isset($_GET['_nonce']))
            $nonce=$_GET['_nonce'];
        if(isset($_POST['_nonce']))
            $nonce=$_POST['_nonce'];

        if($nonce==''||!\wp_verify_nonce($nonce, $this->prefix.'_'.$nonceName))
            $this->SendErrorMessage(_('Invalid request, please refresh the screen and try again'));
    }
}