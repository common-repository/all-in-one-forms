<?php

namespace rednaoeasycalculationforms\PublicApi;

use rednaoeasycalculationforms\core\Loader;
use Vtiful\Kernel\Format;

class PublicApi
{
    /** @var Loader */
    private $loader;

    private $FormApi=null;
    private $EntryApi=null;
    public function __construct()
    {
        $val='';
        $this->loader=apply_filters('allinoneforms_get_loader',$val);
    }

    public function GetLoader(){
        return $this->loader;
    }

    public function GetTwigManager($paths=[],$extensions=[]){
        return $this->loader->GetTwigManager($paths,$extensions);
    }

    public function Form(){
        if($this->FormApi==null)
            $this->FormApi=new FormApi();

        return $this->FormApi;
    }

    public function Entry()
    {
        if ($this->EntryApi == null)
            $this->EntryApi = new EntryApi();

        return $this->EntryApi;
    }
}