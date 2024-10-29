<?php

namespace rednaoeasycalculationforms\pr\ajax;

use rednaoeasycalculationforms\ajax\AjaxBase;
use rednaoeasycalculationforms\pr\Utilities\Activator;

class PRSettings extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'Settings';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('activate_license','ActivateLicense');
        $this->RegisterPrivate('deactivate_license','DeactivateLicense');

    }

    public function ActivateLicense(){

        $licenseKey=$this->GetRequired('LicenseKey');
        $expirationDate=$this->GetRequired('ExpirationDate');
        $url=$this->GetRequired('URL');
        (new Activator())->SaveLicense($licenseKey,$expirationDate,$url);
        $this->SendSuccessMessage('');
    }




    public function DeactivateLicense(){
        Activator::DeleteLicense();

        $this->SendSuccessMessage('');
    }

}