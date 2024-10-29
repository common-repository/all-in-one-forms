<?php


namespace rednaoeasycalculationforms\core\db;


use Exception;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\OptionsManager;
use rednaoeasycalculationforms\core\db\core\RepositoryBase;
use rednaoeasycalculationforms\core\Integration\IntegrationURL;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Utils\IdUtils;
use rednaoeasycalculationforms\DTO\LogOptions;
use stdClass;

class LinkRepository
{
    /** @var Loader */
    public $Loader;
    /** @var DBManager */
    public $DBManager;

    public function __construct($loader)
    {
        $this->Loader=$loader;
        $this->DBManager=new DBManager();
    }



   public function CreateLink($entryId,$options)
   {
       $reference=IdUtils::GetUniqueId();
       $this->DBManager->Insert($this->Loader->LINKS,array(
          'reference'=>$reference,
          'entry_id'=>$entryId,
          'date'=>date('c'),
           'expiration_date'=>date('c',strtotime('+1 month')),
           'options'=>\json_encode($options)
       ));



       return IntegrationURL::PublicEntryURL($this->Loader,$entryId,$reference);

   }

    /**
     * @param $reference
     * @return LinkData
     */
   public function GetLinkData($entryId,$reference)
   {
       $result=$this->DBManager->GetResult('select entry_id, options from '.$this->Loader->LINKS
            .' where entry_id=%s and reference=%s and date<expiration_date',$entryId,$reference);

       if($result==null)
           return null;

       $options=\json_decode($result->options);
       $linkOptions= (new LinkOptions())->Merge($options);
       return new LinkData($result->entry_id,$linkOptions);
   }



}

class LinkData{
    public $EntryId;
    /** @var LinkOptions */
    public $LinkOptions;

    public function __construct($entryId,$linkOptions)
    {
        $this->EntryId=$entryId;
        $this->LinkOptions=$linkOptions;
    }


}

class LinkOptions{
    public $AllowEdition;
    public $RequiresAuthentication;

    public function __construct($allowEdition=false,$requiresAuthentication=false)
    {
        $this->AllowEdition=$allowEdition;
        $this->RequiresAuthentication=false;
    }

    public function Merge($data)
    {
        foreach($this as $propertyName=>$value)
        {
            if(isset($data->$propertyName))
                $this->$propertyName=$data->$propertyName;
        }

        return $this;
    }


}