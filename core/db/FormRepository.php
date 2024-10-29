<?php


namespace rednaoeasycalculationforms\core\db;


use Exception;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\OptionsManager;
use rednaoeasycalculationforms\core\db\core\RepositoryBase;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\DTO\BuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\FormBuilderOptions;
use rednaoeasycalculationforms\DTO\FormBuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\FormulaOptions;
use rednaoeasycalculationforms\DTO\ServerOptionsDTO;
use stdClass;

class FormRepository extends RepositoryBase
{
    /**
     * @param $builderOptions BuilderOptionsDTO
     * @return int
     * @throws Exception
     */
    public function SaveForm($builderOptions,$findUniqueName=false){
        if($builderOptions->Name=='')
            throw new Exception('Name can not be empty');

        if($builderOptions->Id==0)
        {
            $result=$this->DBManager->GetResults('select form_id from '.$this->Loader->FORM_LIST_TABLE.' where form_name=%s',$builderOptions->Name);

            if(count($result))
            {
                if($findUniqueName)
                {
                    $isUnique=false;
                    $count=1;
                    $originalName=$builderOptions->Name;
                    while(!$isUnique){
                        $count++;
                        $builderOptions->Name=$originalName.' '.$count;
                        $result=$this->DBManager->GetResults('select form_id from '.$this->Loader->FORM_LIST_TABLE.' where form_name=%s',$builderOptions->Name);
                        $isUnique=count($result)==0;
                    }

                }else
                    throw new Exception('Name is already in use');
            }

            $icons=$builderOptions->FormBuilder->ClientOptions->Icons;
            unset($builderOptions->FormBuilder->ClientOptions->Icons);

            $emails=$builderOptions->ServerOptions->Emails;
            unset($builderOptions->ServerOptions->Emails);

            $extensions=$builderOptions->ServerOptions->Extensions;
            unset($builderOptions->ServerOptions->Extensions);

            $builderOptions->Id= $this->DBManager->Insert($this->Loader->FORM_LIST_TABLE,array(
                'creation_date'=>date('c'),
                'update_date'=>date('c'),
                'form_name'=>$builderOptions->Name,
                'element_options'=>\json_encode($builderOptions->FormBuilder->Rows),
                'client_form_options'=>\json_encode($builderOptions->FormBuilder->ClientOptions),
                'server_options'=>\json_encode($builderOptions->ServerOptions),
                'emails'=>\json_encode($emails),
                'extension_options'=>\json_encode($extensions),
                'dependencies'=>\json_encode($builderOptions->FormBuilder->Dependencies),
                'icons'=>\json_encode($icons)
            ));

        }else{

            $result=$this->DBManager->GetResults('select form_id from '.$this->Loader->FORM_LIST_TABLE.' where form_name=%s and form_id!=%d',$builderOptions->Name,$builderOptions->Id);

            if(count($result))
            {
                throw new Exception('Name is already in use');
            }

            $icons=$builderOptions->FormBuilder->ClientOptions->Icons;
            unset($builderOptions->FormBuilder->ClientOptions->Icons);

            $emails=$builderOptions->ServerOptions->Emails;
            unset($builderOptions->ServerOptions->Emails);

            $extensions=$builderOptions->ServerOptions->Extensions;
            unset($builderOptions->ServerOptions->Extensions);

            $this->DBManager->Update($this->Loader->FORM_LIST_TABLE,array(
                'form_name'=>$builderOptions->Name,
                'update_date'=>date('c'),
                'element_options'=>\json_encode($builderOptions->FormBuilder->Rows),
                'client_form_options'=>\json_encode($builderOptions->FormBuilder->ClientOptions),
                'server_options'=>\json_encode($builderOptions->ServerOptions),
                'emails'=>\json_encode($emails),
                'extension_options'=>\json_encode($extensions),
                'dependencies'=>\json_encode($builderOptions->FormBuilder->Dependencies),
                'icons'=>\json_encode($icons)
            ),array('form_id'=>$builderOptions->Id));
        }

        return $builderOptions->Id;
    }

    /**
     * @param $formId
     * @param
     * @return FormBuilder
     */
    public function GetForm($formId,$loadServerOptions=false)
    {
        $columns='form_id,form_name,element_options,client_form_options,icons,dependencies';

        if($loadServerOptions)
            $columns.=',emails,server_options,extension_options';

        $data=$this->DBManager->GetResult('select '.$columns.' from '.$this->Loader->FORM_LIST_TABLE.' where form_id=%s',$formId);
        if($data==null)
            return null;

        $rawOptions=new stdClass();
        $rawOptions->Name=$data->form_name;
        $rawOptions->Id=$data->form_id;
        $rawOptions->FormBuilder=new stdClass();
        $rawOptions->FormBuilder->Rows=\json_decode($data->element_options);
        $rawOptions->FormBuilder->ClientOptions=\json_decode($data->client_form_options);
        $rawOptions->FormBuilder->ClientOptions->Icons=\json_decode($data->icons);
        $rawOptions->FormBuilder->Dependencies=\json_decode($data->dependencies);

        if($loadServerOptions)
        {
            $rawOptions->ServerOptions=\json_decode($data->server_options);
            $rawOptions->ServerOptions->Emails=\json_decode($data->emails);
            $rawOptions->ServerOptions->Extensions=\json_decode($data->extension_options);
        }

        $builder=new BuilderOptionsDTO();
        $builder->Merge($rawOptions);
        return new FormBuilder($this->Loader,$builder);

    }

    public function GetNextFileId(){
        $options=new OptionsManager();
        $nextId= $options->GetOption('easy_calculation_file_id',0);
        $nextId++;
        $options->SaveOptions('easy_calculation_file_id',$nextId);
        return $nextId;

    }

    /**
     * @return FormBuilder[]
     */
    public function GetForms($loadServerOptions=false){
        $rows= $this->DBManager->GetResults('select form_id,form_name, element_options,client_form_options, emails,dependencies,server_options,extension_options,icons from '.$this->Loader->FORM_LIST_TABLE);
        $forms=array();

        foreach($rows as $data)
        {
            $rawOptions=new stdClass();
            $rawOptions->Name=$data->form_name;
            $rawOptions->Id=$data->form_id;
            $rawOptions->FormBuilder=new stdClass();
            $rawOptions->FormBuilder->Rows=\json_decode($data->element_options);
            $rawOptions->FormBuilder->ClientOptions=\json_decode($data->client_form_options);
            $rawOptions->FormBuilder->ClientOptions->Icons=\json_decode($data->icons);
            $rawOptions->FormBuilder->Dependencies=\json_decode($data->dependencies);

            if($loadServerOptions)
            {
                $rawOptions->ServerOptions=\json_decode($data->server_options);
                $rawOptions->ServerOptions->Emails=\json_decode($data->emails);
                $rawOptions->ServerOptions->Extensions=\json_decode($data->extension_options);
            }

            $builder=new BuilderOptionsDTO();
            $builder->Merge($rawOptions);
            $forms[]=new FormBuilder($this->Loader, $builder);
        }

        return $forms;
    }

    public function Delete($formId)
    {
        $this->DBManager->Delete($this->Loader->FORM_LIST_TABLE,array('form_id'=>$formId));
    }
}

class FormDataDTO{
    /** @var FormBuilderOptions */
    public $FormOptions;
    public $ServerOptions;


    public function GetExtensionServerOptions($extensionId)
    {
        if($this->ServerOptions==null)
            return null;
        return ArrayUtils::Find($this->ServerOptions->Extensions,function ($item)use($extensionId){return $item->Id==$extensionId;});

    }
}