<?php


namespace rednaoeasycalculationforms\pr\Managers\EntryLoader;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\EntryRepository;
use rednaoeasycalculationforms\core\Integration\DateIntegration;
use rednaoeasycalculationforms\core\Integration\UserInfo;
use rednaoeasycalculationforms\core\Integration\UserIntegration;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FormLoader\FormLoader;
use rednaoeasycalculationforms\Managers\QueryManager\QueryManager;

class EntryLoader
{
    /** @var Loader */
    public $Loader;
    /** @var FormLoader */
    public $formLoader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
        $this->formLoader=new FormLoader($this->Loader);
    }

    public function LoadEntry($EntryId)
    {
        $entryRepository=new EntryRepository($this->Loader);
        $data=$entryRepository->LoadEntry($EntryId);
        if($data==null)
            return false;

        $queryManager=new QueryManager($this->Loader,$data->FormId);
        $data=$queryManager->CreateWhereGroup()->AddEntryId($EntryId);
        $data=$queryManager->GetResult();




        if($this->formLoader->LoadFromId($data->FormId)===false)
            return false;

        $userIntegration=new UserIntegration($this->Loader);
        $user=$userIntegration->GetUserInfoById($data->UserId);

        $this->formLoader->LoadEntry((object)array(
            'Fields'=>$data->Data,
            'EntryId'=>$EntryId,
            'ReferenceId'=>$data->ReferenceId,
            'Sequence'=>$data->Sequence,
            'UserName'=>$user->Name,
            'UserEmail'=>$user->Email,
            'Status'=>$data->Status,
            'UnixDate'=>$data->UnixDate,
            'EditNonce'=>wp_create_nonce('edit_entry_'.$EntryId)

        ));
        //$this->formLoader->SetEditNonce(\wp_create_nonce($EntryId.'__'.$data->ReferenceId));
        if(\current_user_can('administrator'))
            $this->formLoader->SetAllowStatusEdition();

        $dateIntegration=new DateIntegration($this->Loader);
        $this->formLoader->SetTimeOffset($dateIntegration->GetSiteTimeOffset());



        return true;

    }

    public function LoadEntryByReference($reference)
    {
        $dbManager=new DBManager();
        $entry=$dbManager->GetResult('select entry_id from '.$this->Loader->RECORDS_TABLE.' where reference_id=%s',$reference);
        if($entry===null)
            return false;

        return $this->LoadEntry($entry->entry_id);
    }


    public function SetAllowEdition($allowEdition){
        $this->formLoader->SetAllowEdition($allowEdition);
    }

    public function Load(){
        return $this->formLoader->Load();
    }

}