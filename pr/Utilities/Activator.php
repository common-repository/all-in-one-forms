<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/7/2018
 * Time: 5:13 AM
 */

namespace rednaoeasycalculationforms\pr\Utilities;





class Activator
{
    /** @var License */
    public static $License=null;

    public static function SaveLicense($licenseKey,$expirationDate,$url)
    {
        $license=new License();
        $license->ExpirationDate=$expirationDate;
        $license->LicenseKey=$licenseKey;
        $license->URL=$url;
        self::$License=$license;
        update_option('rnwcinv_license',json_encode($license));
    }


    public static function SetRenewNotice($loader,$renewNotice)
    {
        \update_option('rnwcinv_renew_notice',$renewNotice);
    }


    public static function ShouldShowRenewNotice($loader)
    {
        \get_option($loader->Prefix.'rnwcinv_renew_notice',false)==true;
    }

    public static function GetLicense($loader=null){
        if(self::$License==null)
        {
            self::$License = \json_decode(\get_option('rnwcinv_license', ''));
            if (self::$License== false)
                self::$License=new License();
        }
        return self::$License;
    }

    public static function GetLicenseKey()
    {
        $license=self::GetLicense();
        if($license==null)
            return '';
        return $license->LicenseKey;
    }

    public static function DeleteLicense()
    {
        \delete_option('rnwcinv_license');
        self::$License=null;
    }

    public static function LicenseIsActive()
    {
        $license=Activator::GetLicense();
        if($license->LicenseKey=='')
            return false;

        return $license->ExpirationDate>time();

    }

    public static function HasLicense(){
        return Activator::GetLicense()->LicenseKey!='';
    }


}

class License{
    public $LicenseKey;
    public $ExpirationDate;
    public $URL;

    /**
     * License constructor.
     * @param License $data
     */
    public function __construct($data=null)
    {
        if($data==null)
        {
            $this->LicenseKey='';
            $this->ExpirationDate='';
            $this->URL;
        }else{
            $this->LicenseKey=$data->LicenseKey;
            $this->ExpirationDate=$data->ExpirationDate;
            $this->URL=$data->URL;
        }
    }


}