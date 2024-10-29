<?php


namespace rednaoeasycalculationforms\pr\ajax;


use Exception;
use rednaoeasycalculationforms\ajax\AjaxBase;
use rednaoeasycalculationforms\core\db\EntryRepository;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;
use rednaoeasycalculationforms\DTO\FilterConditionOptionsDTO;
use rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\CSVQueryFormatter;
use rednaoeasycalculationforms\Managers\QueryManager\QueryManager;
use rednaoeasycalculationforms\pr\Managers\Editor\FormEntryEditor;

class PREntriesAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'Entries';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('update_field','UpdateField');

        $this->RegisterPrivate('export_entries','ExportEntries');
        $this->RegisterPublic('entry_edited','EditEntry',false);
    }

    public function ExportEntries(){
        $queryManager=new QueryManager($this->Loader,$this->GetRequired('Form'));
        $queryManager->QueryFormatter=new CSVQueryFormatter($queryManager);

        $startDate=$this->GetOptional('StartDate',0);
        $endDate=$this->GetOptional('EndDate',0);
        $condition=$this->GetOptional('FilterCondition',null);

        if($startDate>0||$endDate>0)
        {

            $whereGroup=$queryManager->CreateWhereGroup();

            if($startDate>0)
                $whereGroup->AddStartDate($startDate);

            if($endDate>0)
                $whereGroup->AddEndDate($endDate+(60*60*24));
        }
        $condition=(new FilterConditionOptionsDTO())->Merge($condition);
        if($condition!=null&&count($condition->ConditionGroups)>0)
        {
            $queryManager->AddCondition($condition);

        }



        $results=$queryManager->GetResults();

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . str_replace('"', '\"', 'Export')  . '.csv";');
        header('Content-Transfer-Encoding: binary');

        $fh = fopen('php://output', 'w');
        $csvRow=array();
        foreach($results->Columns as $columnName)
        {
            $csvRow[]=$columnName;
        }

        fputcsv($fh,$csvRow);


        foreach($results->Data as $rows)
        {
            $csvRow=array();
            foreach($rows as $column)
            {
                $csvRow[]=$column;
            }
            fputcsv($fh,$csvRow);
        }

        $csvRow=array();



        $count=$queryManager->GetCount();
    }


    public function UpdateField(){
        $nonce=$this->GetRequired('Nonce');
        $entryId=$this->GetRequired('EntryId');
        $fieldId=$this->GetRequired('FieldId');
        $value=$this->GetRequired('Value');

        if(!\wp_verify_nonce($nonce,'edit_entry_'.$entryId))
            $this->SendErrorMessage('Invalid Request');

        $formEntryEditor=new FormEntryEditor($this->Loader);
        if(!$formEntryEditor->Initialize($entryId))
            $this->SendErrorMessage('Form not found');

        try{
            $formEntryEditor->UpdateAndSaveEntryInformation($fieldId,$value);
            if($fieldId=='_created_by')
                $value=[
                    "UserId"=>$formEntryEditor->Entry->UserId,
                    "UserEmail"=>$formEntryEditor->Entry->UserEmail,
                    "UserName"=>$formEntryEditor->Entry->UserName
                ];
            $this->SendSuccessMessage($value);
        }catch (Exception $ex)
        {
            $this->SendErrorMessage($ex);
        }
    }


    public function EditEntry(){
        $entryId=$this->GetRequired('EntryId');
        $referenceId=$this->GetRequired('ReferenceId');
        $nonce=$this->GetRequired('Nonce');

        if(!\wp_verify_nonce($nonce,'edit_entry_'.$entryId))
            $this->SendErrorMessage('Invalid Request');


        $entry=$this->GetRequired('Entry');

        /** @var FormEntryEditor $formEntryEditor */

        $formEntryEditor=new FormEntryEditor($this->Loader);


        if(!$formEntryEditor->Initialize($entryId))
            $this->SendErrorMessage('Form not found');



        \add_filter('wp_die_ajax_handler',function () use($formEntryEditor){
            $error =\error_get_last();
            $friendlyException=new FriendlyException('An unexpected error ocurred, please try again',$error['message']);
            $this->SendException($friendlyException,"An error occurred",$formEntryEditor->FormBuilder->DebugModeEnabled);
            die();
        });
        $action=array();
        try
        {
            $action=$formEntryEditor->ProcessEntry($entry);
            $text=\ob_get_clean();
            if($text!='')
                LogManager::Log(LogManager::TYPE_DEBUG,"Additional information printed while submitting the form:".$text);
        }catch(Exception $e)
        {
            $text=\ob_get_clean();
            if($text!='')
                LogManager::Log(LogManager::TYPE_DEBUG,"Additional information printed while submitting the form:".$text);

            $this->SendException($e,"",$formEntryEditor->FormBuilder->DebugModeEnabled);
        }

        $entryRepository=new EntryRepository($this->Loader);

        $queryManager=new QueryManager($this->Loader,$formEntryEditor->FormId);
        $queryManager->CreateWhereGroup()->AddEntryId($entryId);

        $this->SendSuccessMessage(array('Entry'=>$queryManager->GetResults()[0],'Action'=>$action));


    }



}