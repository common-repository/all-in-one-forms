<?php


namespace rednaoeasycalculationforms\ajax;


use Exception;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\EntrySaver\FormEntrySaver;
use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;

class SubmissionAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'Submission';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPublic('form_submitted','ProcessSubmission',false);
    }

    public function ProcessSubmission(){

        \ob_start();

        $entry=$this->GetRequired('Entry');
        $formEntrySaver=new FormEntrySaver($this->Loader);
        $formEntrySaver->Initialize($entry->FormId);


        \add_filter('wp_die_ajax_handler',function () use($formEntrySaver){
            $error =\error_get_last();
            $friendlyException=new FriendlyException('An unexpected error occurred, please try again',$error['message']);
            $this->SendException($friendlyException,"An error occurred",$formEntrySaver->FormBuilder->DebugModeEnabled);
            die();
        });



        if($formEntrySaver==null)
            $this->SendErrorMessage('Form not found');
        else
            try
            {
                $meta=[];
                $requestURL=esc_url($this->GetOptional('RequestURL'));
                if(!empty($requestURL))
                    $meta['origin_url']=[
                        'value'=>$requestURL,
                        'isVisible'=>false
                    ];
                $action=$formEntrySaver->ProcessEntry($entry,$meta);
                $text=\ob_get_clean();
                if($text!='')
                    LogManager::Log(LogManager::TYPE_DEBUG,"Additional information printed while submitting the form:".$text);

                if($formEntrySaver->SaveSuccess)
                    $this->SendSuccessMessage(array(
                        'success'=>true,
                        'Action'=>$action,
                        'Entry'=>array(
                            'ReferenceId'=>$formEntrySaver->FormBuilder->GetReference(),
                            'Sequence'=>$formEntrySaver->FormBuilder->GetSubmissionNumber(),
                            'Total'=>$formEntrySaver->FormBuilder->GrandTotal
                        )
                    ));
                else
                    $this->SendSuccessMessage(array(
                        'success'=>false,
                        'Action'=>$action
                    ));
            }catch(Exception $e)
            {
                $text=\ob_get_clean();
                if($text!='')
                    LogManager::Log(LogManager::TYPE_DEBUG,"Additional information printed while submitting the form:".$text);

                if($formEntrySaver->FormBuilder==null)
                    $formEntrySaver->CreateFormBuilder();
                $this->SendException($e,"",$formEntrySaver->FormBuilder->DebugModeEnabled);
            }


        $this->SendSuccessMessage(true);



    }
}