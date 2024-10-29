<?php


namespace rednaoeasycalculationforms\pr\ajax;


use Exception;
use rednaoeasycalculationforms\ajax\AjaxBase;
use rednaoeasycalculationforms\core\Managers\ExportManager\ImportManager;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\Managers\Editor\FormEntryEditor;

class FormListPRAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'FormList';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('create_from_remote_template','CreateFromRemoteTemplate');
    }

    public function CreateFromRemoteTemplate(){
        $templateId=$this->GetRequired('TemplateId');

        $templateId=\intval($templateId);
        $response=\wp_remote_get('https://allinoneforms.rednao.com/wp-content/uploads/sites/5/smart_forms_templates/template_'.$templateId.'/Export.zip');

        if(\is_wp_error($response))
            $this->SendErrorMessage('Could not load the template error:'.$response->get_error_message());

        $fileManager=new FileManager($this->Loader);
        $path=$fileManager->GetTempPath().'Export.zip';
        \file_put_contents($path,$response['body']);

        $content=$importManager=new ImportManager($this->Loader);
        try{
            $content=$importManager->Import($path);
            $this->SendSuccessMessage(array('Id'=>$content->FormOptions->Id));
        }catch (Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
        }




    }

}