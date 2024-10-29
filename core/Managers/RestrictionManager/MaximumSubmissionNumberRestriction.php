<?php


namespace rednaoeasycalculationforms\core\Managers\RestrictionManager;


use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\Integration\UserIntegration;
use rednaoeasycalculationforms\core\Managers\EntrySaver\ConfirmationActions\MessageConfirmationAction;
use rednaoeasycalculationforms\DTO\NumberOfSubmissionsRestrictionOption;
use rednaoeasycalculationforms\DTO\NumberOfSubmissionsRestrictionOptionDTO;

class MaximumSubmissionNumberRestriction
{
    /** @var $Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    /**
     * @param $restriction NumberOfSubmissionsRestrictionOptionDTO
     * @param $formId
     */
    public function ValidateRestriction($restriction, $formId){

        $dbManager=new DBManager();
        $count=0;
        if($restriction->CountingType=='Total')
            $count=$dbManager->GetVar('select count(*) from '.$this->Loader->RECORDS_TABLE.' where form_id=%s',$formId);
        else{
            $userIntegration=new UserIntegration($this->Loader);
            $id=$userIntegration->GetCurrentUserId();
            if($id==0)
            {
                return new MessageConfirmationAction('', __("You must be logged in to submit this form"), __('Close','all-in-one-forms'), MessageConfirmationAction::$ICON_TYPE_ERROR);
            }

            $count=$dbManager->GetVar('select count(*) from '.$this->Loader->RECORDS_TABLE.' where form_id=%s and user_id=%s',$formId,$id);
        }

        return $count>=$restriction->NumberOfSubmissions;
    }

}