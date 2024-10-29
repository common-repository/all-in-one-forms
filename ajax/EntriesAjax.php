<?php


namespace rednaoeasycalculationforms\ajax;


use Exception;
use rednaoeasycalculationforms\core\db\EntryRepository;
use rednaoeasycalculationforms\core\db\FormDataDTO;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\EmailManager\EmailManager;
use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;
use rednaoeasycalculationforms\DTO\FilterConditionOptionsDTO;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\CSVQueryFormatter;
use rednaoeasycalculationforms\Managers\QueryManager\QueryManager;
use rednaoeasycalculationforms\pr\Managers\Editor\FormEntryEditor;

class EntriesAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'Entries';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('search_entries','SearchEntries',['aio_view_entries']);
        $this->RegisterPrivate('load_entry','LoadEntry');
        $this->RegisterPrivate('delete_entries','DeleteEntry');
        $this->RegisterPrivate('save_status','SaveStatus');
        $this->RegisterPrivate('query_users','QueryUsers');
        $this->RegisterPrivate('get_extra_options','GetFormOptions');
        $this->RegisterPrivate('get_entry_actions','GetEntryActions',['aio_view_entries']);
        $this->RegisterPrivate('resend_email','ResendEmail');
    }

    public function ResendEmail(){
        $entryId=$this->GetRequired('EntryId');
        $emailId=$this->GetRequired('EmailId');


        /** @var FormEntryEditor $form */
        $form=AllInOneForms()->Entry()->Get($entryId,'edit');

        if($form!=null)
        {
            $form->SendEmail($emailId);
        }
        $this->SendSuccessMessage(true);
    }

    public function GetEntryActions(){
        $formId=$this->GetRequired('FormId');
        $entryId=$this->GetRequired('EntryId');
        $content='';

        $sections='';

        $content=apply_filters('allinoneforms_get_additional_actions',$content,$formId,$entryId);
        $sections=apply_filters('allinoneforms_get_additional_action_sections',$sections,$formId,$entryId);
        $this->SendSuccessMessage(["Content"=>$content,"Sections"=>$sections]);
    }

    public function GetFormOptions(){
        $formId=$this->GetRequired('formId');
        $formRepository=new FormRepository();
        $form=$formRepository->GetForm($formId,true);

        $options=array(
            'FormId'=>$form->Options->Id,
            'Options'=>$form->Options->FormBuilder,
            'Extra'=>[]
        );

        require_once $this->Loader->DIR.'core/Managers/FormLoader/ServerDependencyHooks.php';
        if(count($form->Options->ServerOptions->ServerDependencies)>0)
            foreach($form->Options->ServerOptions->ServerDependencies as $currentDependency)
            {
                $options=apply_filters('allinoneforms_get_server_dependency_'.$currentDependency->Type,$options,$currentDependency);
            }
        $this->SendSuccessMessage($options['Extra']);
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


    public function SaveStatus(){
        $entryId=$this->GetRequired('EntryId');
        $status=$this->GetRequired('Status');

        $repository=new EntryRepository($this->Loader);
        $repository->SaveStatus($entryId,$status);
        $this->SendSuccessMessage(true);

    }


    public function LoadEntry(){
        $entryId=$this->GetRequired('EntryIds');


        if(!\is_array($entryId))
            $entryId=array($entryId);
        $repository=new EntryRepository($this->Loader);


        $results=array();

        /** @var FormDataDTO $currentForm */
        $currentForm=null;
        foreach($entryId as $currentEntry)
        {
            $entry=$repository->LoadEntry($currentEntry);
            $formRepository = new FormRepository($this->Loader);
            if($currentForm==null)
                $currentForm = $formRepository->GetForm($entry->FormId)->Options;
            else{
                if($currentForm->FormOptions->Id!=$entry->FormId)
                    $this->SendErrorMessage('All the entries must belong to the same form');
            }



            $queryManager=new QueryManager($this->Loader,$entry->FormId);
            $queryManager->CreateWhereGroup()->AddEntryId($currentEntry);

            $rowResult=$queryManager->GetResults();
            if(count($rowResult)>0)
                $results[]=$rowResult[0];
        }



        $this->SendSuccessMessage(array(
            'Entries'=>$results,
            'Id'=>$currentForm->FormOptions->Id,
            'Form'=>$currentForm->FormOptions->Rows,
            'Name'=>$currentForm->FormOptions->Name,
            'ClientOptions'=>$currentForm->FormOptions->ClientOptions
        ));
    }

    public function DeleteEntry(){
        $ids=$this->GetRequired('Ids');

        $entryRepository=new EntryRepository($this->Loader);
        foreach($ids as $currentId)
        {
            $entryRepository->DeleteEntry($currentId);
        }

        $this->SendSuccessMessage('Record(s) deleted successfully');

    }

    public function SearchEntries(){
        $queryManager=new QueryManager($this->Loader,$this->GetRequired('Form'));
        $whereGroup=$queryManager->CreateWhereGroup();

        $startDate=$this->GetOptional('StartDate',0);
        $endDate=$this->GetOptional('EndDate',0);
        $pageIndex=$this->GetRequired('PageIndex');
        $pageSize=$this->GetRequired('PageSize');
        $condition=$this->GetOptional('FilterCondition',null);

        if($startDate>0||$endDate>0)
        {

            $whereGroup=$queryManager->CreateWhereGroup();

            if($startDate>0)
                $whereGroup->AddStartDate($startDate);

            if($endDate>0)
                $whereGroup->AddEndDate($endDate+(60*60*24));
        }

        $whereGroup=$queryManager->CreateWhereGroup();
        $whereGroup->AddWhereStatement('_is_visible','NotEqual',0);


        do_action('allinoneforms_before_entry_search',$queryManager,$condition);


        $results=$queryManager->GetResults($pageSize,$pageIndex*$pageSize);

        foreach($results as $currentRow)
        {
            $currentRow->EditNonce=wp_create_nonce('edit_entry_'.$currentRow->EntryId);
        }
        $count=$queryManager->GetCount();



        $this->SendSuccessMessage(array('Rows'=>$results,'Count'=>$count));
    }
}