<?php


namespace rednaoeasycalculationforms\ajax;



use Exception;
use rednaoeasycalculationforms\ajax\AjaxSanitizer\NumberArrayAjaxSanitizer;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\OptionsManager;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Integration\DateIntegration;
use rednaoeasycalculationforms\core\Managers\ExportManager\ExportManager;
use rednaoeasycalculationforms\core\Managers\ExportManager\ImportManager;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLGenerator;
use rednaoeasycalculationforms\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rednaoeasycalculationforms\core\Managers\SlateTextGenerator\SlateTextGenerator;
use rednaoeasycalculationforms\DTO\BuilderOptionsDTO;
use rednaoeasycalculationforms\DTO\FormBuilderOptionsDTO;
use rednaoeasycalculationforms\Utilities\ObjectSanitizer;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class FormListAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'FormList';
    }


    protected function RegisterHooks()
    {
        $this->RegisterPrivate('clone_form','CloneForm');
        $this->RegisterPrivate('dont_show_assistance_again','DontShowAssistanceAgain');
        $this->RegisterPrivate('list_form','ListForm');
        $this->RegisterPrivate('save_form','SaveForm');
        $this->RegisterPrivate('delete_form','Delete');
        $this->RegisterPrivate('get_text_editor_preview','GetTextEditorPreview');
        $this->RegisterPrivate('export_form','ExportForm');
        $this->RegisterPrivate('import_form','ImportForm');
        $this->RegisterPrivate('get_next_number','GetNextNumber');
        $this->RegisterPrivate('update_sequence_number','UpdateSequenceNumber');
        $this->RegisterPrivate('create_from_local_template','CreateFromLocalTemplate');
        $this->RegisterPrivate('get_local_preview','GetLocalPreview');
        $this->RegisterPrivate('list_users','QueryUsers','administrator',true,'alloinoneforms_list_users');
        $this->RegisterPrivate('load_users_by_id','LoadUsersById','administrator',true,'alloinoneforms_list_users');
        $this->RegisterPrivate('save_csv_file','SaveCSVFile');
        $this->RegisterPrivate('remove_csv_file','RemovingCSVFile');
        $this->RegisterPublic('get_csv_items','GetCSVItems',false);

    }

    public function GetCSVItems(){
        $ids=$this->GetRequired('ids');
        $nonce=$this->GetRequired('nonce');
        $formId=$this->GetRequired('formId');
        $fieldId=$this->GetRequired('fieldId');

        if(!wp_verify_nonce($nonce,'allinoneforms_csv_'.$formId.'_'.$fieldId))
            $this->SendErrorMessage(__('Invalid request please refresh the screen and try again',"all-in-one-forms"));

        $fileManager=new FileManager((AllInOneForms())->GetLoader());
        $content=$fileManager->GetAssetString($formId,$fieldId.'.json');
        if($content===null)
            $this->SendSuccessMessage(__('No items found',"all-in-one-forms"));

        $content=json_decode($content,true);
        if($content==null)
            $this->SendSuccessMessage(__('No items found',"all-in-one-forms"));



        foreach($ids as $currentId)
        {
            if(!isset($content[$currentId]))
                $this->SendErrorMessage(__('No  item found',"all-in-one-forms"));
            $content=$content[$currentId]['Children'];

        }

        $items=[];
        foreach($content as $key=>$value)
        {
            $currentItem=[
                "L"=>$key,

            ];

            if(isset($value['Price']))
                $currentItem['P']=$value['Price'];
            $items[]=$currentItem;
        }

        usort($items, function ($a, $b) {
            return strcmp($a['L'], $b['L']);
        });
        $this->SendSuccessMessage($items);

    }

    public function RemovingCSVFile(){
        $formId=$this->GetRequired('FormId');
        $fieldId=$this->GetRequired('FieldId');

        $fileManager=new FileManager($this->Loader);
        $fileManager->MaybeDeleteAsset($formId,$fieldId.'.json');
        $this->SendSuccessMessage(true);
    }

    public function SaveCSVFile(){
        $formId=$this->GetRequired('FormId');
        $fieldId=$this->GetRequired('FieldId');
        $useLastColumnAsPrice=$this->GetRequired('UseLastColumnAsPrice');

        if(!is_numeric($fieldId)||!is_numeric($formId)||$formId==0)
            $this->SendErrorMessage(__('Invalid field, please try again',"all-in-one-forms"));


        #check if file is csv
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if (!isset($_FILES['File']['tmp_name']))
            $this->SendErrorMessage(__('Invalid file, please try again',"all-in-one-forms"));

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['File']['tmp_name']);
        finfo_close($finfo);
        if(in_array($mime, $csvMimes))
        {
            $file=fopen($_FILES['File']['tmp_name'],'r');
            $firstLine=fgetcsv($file,1000,',');

            if($useLastColumnAsPrice)
            {
                unset($firstLine[count($firstLine)-1]);
            }
            $content=[];
            while(($lineColumns=fgetcsv($file,10000,','))!==false){
                $pointer=&$content;
                if($useLastColumnAsPrice)
                {
                    $price=$lineColumns[count($lineColumns)-1];
                    unset($lineColumns[count($lineColumns)-1]);
                    $lineColumns[count($lineColumns)-1].='|'.$price;
                }
                foreach($lineColumns as $currentColumn)
                {

                    $currentColumn=explode('|',$currentColumn);
                    $name='';
                    $price=0;


                    if(count($currentColumn)==2)
                    {
                        $name=trim($currentColumn[0]);
                        $price=trim($currentColumn[1]);
                    }
                    else{
                        $name=trim($currentColumn[0]);
                    }

                    if($name=='')
                        continue;

                    if(!isset($pointer[$name]))
                    {
                        $pointer[$name]=[

                            'Children'=>[]
                        ];

                        if($price!=0)
                            $pointer[$name]['Price']=$price;
                    }

                    $pointer=&$pointer[$name]['Children'];

                }
            }

            fclose($file);
            if($firstLine===false||count($firstLine)==0)
                $this->SendErrorMessage(__('Invalid file, please try again',"all-in-one-forms"));
            $fileManager=new FileManager($this->Loader);
            if($fileManager->SaveAsset($formId,$fieldId.'.json',json_encode($content))==null)
                $this->SendErrorMessage(__('Error saving file, please try again',"all-in-one-forms"));
        }
        else{
            $this->SendErrorMessage(__('Invalid file, please try again',"all-in-one-forms"));
        }

        $this->SendSuccessMessage([
            'Columns'=>$firstLine,
            'Nonce'=>wp_create_nonce('allinoneforms_csv_'.$formId.'_'.$fieldId)
        ]);





    }

    public function DontShowAssistanceAgain(){
        update_option($this->Loader->Prefix.'_dont_show_again',1);
        $this->SendSuccessMessage(true);
    }

    public function LoadUsersById(){
        $ids=$this->GetRequired('Ids');

        $escapedIds=[];
        $users=[];
        global $wpdb;
        foreach($ids as $currentId)
        {
            $escapedIds[]=intval($currentId);
        }

        $dbmanager=new DBManager();

        if(count($escapedIds)>0)
        {
            $result=$dbmanager->GetResults("
                select ID,firstname.meta_value user_firstname,lastname.meta_value last_name,user_email user_email
                from ".$wpdb->users." usert
                left join ".$wpdb->usermeta." firstname on usert.ID = firstname.user_id and firstname.meta_key='first_name'
                left join ".$wpdb->usermeta." lastname on usert.ID = lastname.user_id and lastname.meta_key='last_name'
                where usert.ID in(" . implode($escapedIds) . ")
            ");

            foreach($result as $currentUser)
            {
                $users[]=[
                    'Label'=>$currentUser->user_firstname.' '.$currentUser->last_name.' ('.$currentUser->user_email.')',
                    'Value'=>$currentUser->ID
                ];
            }
        }
        $this->SendSuccessMessage($users);
    }


    public function QueryUsers(){
        $query=$this->GetRequired('query');
        $wp_user_query = new \WP_User_Query(
            array(
                'search' => "*{$query}*",
                'search_columns' => array(
                    'user_login',
                    'user_nicename',
                    'user_email',
                ),

            ) );
        $users = $wp_user_query->get_results();

//search usermeta
        $wp_user_query2 = new \WP_User_Query(
            array(
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'first_name',
                        'value' => $query,
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => 'last_name',
                        'value' => $query,
                        'compare' => 'LIKE'
                    )
                )
            )
        );

        $users2 = $wp_user_query2->get_results();

        $totalusers_dup = array_merge($users,$users2);

        /** @var \WP_User[] $totalusers */
        $totalusers = array_unique($totalusers_dup, SORT_REGULAR);

        $info=[];
        foreach($totalusers as $currentUser)
        {
            $info[]=[
                'Label'=>$currentUser->user_firstname.' '.$currentUser->last_name.' ('.$currentUser->user_email.')',
                'Value'=>$currentUser->ID
            ];
        }

        $this->SendSuccessMessage($info);
    }


    public function CloneForm(){
        $formId=$this->GetRequired('FormId');
        $formName=$this->GetRequired('Name');

        $repository=new FormRepository($this->Loader);
        /** @var BuilderOptionsDTO $form */
        $form=null;
        try{
            $form=$repository->GetForm($formId,true)->Options;

        }catch (Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
        }
        $form->Id=0;
        $form->Name=$formName;

        $repository->SaveForm($form,true);
        $this->SendSuccessMessage(array('Name'=>$form->Name,'Id'=>$form->Id));

    }

    public function GetLocalPreview(){
        $id=$this->GetRequired('TemplateId');
        $id=preg_replace("/[^a-z0-9.]+/i", "", $id);
        $path=$this->Loader->DIR.'Templates/Locals/'.$id.'/Template.json';
        if(!\file_exists($path))
            $this->SendErrorMessage('Template not found');
        $code=\file_get_contents($path);
        $code=\json_decode($code);

        if($code==null||!isset($code->FormBuilder)||!isset($code->ServerOptions))
            $this->SendErrorMessage('Invalid template');

        $builderOptions=(new BuilderOptionsDTO())->Merge($code);
        $builderOptions->Id=-1;

        $this->SendSuccessMessage(array('FormOptions'=>$builderOptions));

    }

    public function CreateFromLocalTemplate(){
        $id=$this->GetRequired('TemplateId');
        $id=preg_replace("/[^a-z0-9.]+/i", "", $id);
        $path=$this->Loader->DIR.'Templates/Locals/'.$id.'/Template.json';
        if(!\file_exists($path))
            $this->SendErrorMessage('Template not found');

        $code=\file_get_contents($path);
        $code=\json_decode($code);


        if($code==null||!isset($code->FormBuilder)||!isset($code->ServerOptions))
            $this->SendErrorMessage('Invalid template');

        $builderOptions=(new BuilderOptionsDTO())->Merge($code);
        $builderOptions->Id=0;
        $repository=new FormRepository($this->Loader);
        try
        {
            $repository->SaveForm($builderOptions, true);
        }catch(Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
        }

        $this->SendSuccessMessage(array('Id'=>$builderOptions->Id));

    }

    public function UpdateSequenceNumber(){
        $sequenceNumber=$this->GetRequired('SequenceNumber');
        $formId=$this->GetRequired('FormId');

        if(!\is_numeric($sequenceNumber))
        {
            $this->SendErrorMessage('Invalid Number');
            return;
        }

        $options=new OptionsManager();
        $formSequence=$this->Loader->Prefix.'_sequence_'.$formId;
        $nextNumber=$options->SaveOptions($formSequence,$sequenceNumber-1);
        $this->SendSuccessMessage(true);

    }

    public function GetNextNumber(){
        $numberOfDigits=$this->GetRequired('NumberOfDigits');
        $prefix=$this->GetRequired('Prefix');
        $suffix=$this->GetRequired('Suffix');
        $nextNumber=$this->GetRequired('NextNumber');
        $formBuilderModel=$this->GetRequired('Model');

        if($numberOfDigits<0)
            $numberOfDigits=0;

        $nextNumber=\str_pad($nextNumber,$numberOfDigits,'0',\STR_PAD_LEFT);

        $model=(new BuilderOptionsDTO())->Merge();
        $model->FormBuilder=(new FormBuilderOptionsDTO())->Merge($formBuilderModel);
        $model=new FormBuilder($this->Loader,$model,null);
        $model->IsTest=true;
        $model->Initialize();

        $singleLineGenerator=new SingleLineGenerator($model);
        $prefix=$singleLineGenerator->GetText($prefix);
        $suffix=$singleLineGenerator->GetText($suffix);
        $this->SendSuccessMessage(array('Content'=>$prefix.$nextNumber.$suffix));
    }

    public function ImportForm(){
        if(!isset($_FILES['FormToImport']))
        {
            $this->SendErrorMessage('Invalid request');
        }

        $importManager=new ImportManager($this->Loader);
        try
        {
            $content = $importManager->Import($_FILES['FormToImport']['tmp_name']);
        }catch(Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
            return;
        }
        $this->SendSuccessMessage(array('Name'=>$content->Name,'Id'=>$content->Id));
    }

    public function ExportForm(){
        $formId=$this->GetRequired('FormId');

        $repository=new FormRepository($this->Loader);
        $result=$repository->GetForm($formId,true)->Options;
        if($result==null)
        {
            echo "From not found";
            die();
        }

        $exportManager=new ExportManager($this->Loader);
        $exportManager->AddFormTemplate($result);
        $path=$exportManager->CloseAndGetZipPath();

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=".$result->Name.'.zip');
        header("Content-Length: " . filesize($path));
        readfile($path);

        $exportManager->Remove();

        exit;
    }


    public function Delete(){
        $formIds=$this->GetRequired('FormId',(new NumberArrayAjaxSanitizer())->SetConvertScalarToArray());

        $formRepository=new FormRepository($this->Loader);
        foreach ($formIds as $formId)
            $formRepository->Delete($formId);
        $this->SendSuccessMessage('true');

    }

    public function GetTextEditorPreview(){
        $data=$this->GetRequired('Data');
        $options=$this->GetRequired('Options');
        $builderOptions=new BuilderOptionsDTO();
        $builderOptions->FormBuilder=(new FormBuilderOptionsDTO())->Merge($options);

        $formbuilder=new FormBuilder($this->Loader,$builderOptions,null);
        $formbuilder->Initialize();
        $formbuilder->IsTest=true;


        $generator=new HTMLGenerator($formbuilder,$data);
        $generator->SetIsTest();
        $text=$generator->GetInline();

        $this->SendSuccessMessage(array('Text'=>$text));
    }


    public function ListForm($sortBy=null,$pageSize=null,$index=null,$direction=null,$search=null){

        if($sortBy===null)
            $sortBy=$this->GetRequired('SortBy');

        if($pageSize===null)
            $pageSize=$this->GetRequired('PageSize');

        if($index===null)
            $index=$this->GetRequired('Index');

        if($direction===null)
            $direction=$this->GetRequired('Direction');

        if($search==null)
            $search=$this->GetOptional('Search','');

        if($direction!='asc')
            $direction='desc';


        $db=new DBManager();
        $searchFilter='';
        if($search!='')
        {
            $searchFilter=' where (form.form_id='.$db->Escape($search).' or form_name like "%%'.$db->EscapeLike($search).'%%")';
        }


        $result=$db->GetResults("select form.form_id,form_name, creation_date,update_date,(select count(*) from {$this->Loader->RECORDS_TABLE} record where record.form_id=form.form_id  ) count
                                    from {$this->Loader->FORM_LIST_TABLE} form
                                    ".$searchFilter."
                                    order by ".$db->EscSQLName($sortBy)." ".$direction."
                                    limit %d,%d",$index*$pageSize,$pageSize);

        $count=$db->GetVar("select count(*) Count
                                    from {$this->Loader->FORM_LIST_TABLE} form
                                    ".$searchFilter);



        $date=new DateIntegration($this->Loader);

        foreach($result as $currentRow)
        {
            $currentRow->creation_date=$date->GetTimezonedDateFromUTCDate($currentRow->creation_date);
            $currentRow->update_date=$date->GetTimezonedDateFromUTCDate($currentRow->update_date);
        }

        return $this->SendSuccessMessage(array('Result'=>$result,'Count'=>$count));
    }

    public function SaveForm(){
        $builderOptions=(new BuilderOptionsDTO())->Merge($this->GetRequired('Options'));
        $formRepository=new FormRepository($this->Loader);
        try{
            $id=$formRepository->SaveForm($builderOptions);
            do_action('allinoneforms_after_save_form',$builderOptions);
            $this->SendSuccessMessage($id);
        }catch (Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
        }



    }



}