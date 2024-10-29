<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParseUtilities;

class ParseMembE extends ParseBase
{
    private $Subt;
    /** @var ParseBase */
    private $Prop;
    /** @var ParseBase */
    private $Caller;
    /** @var ParseBase[] */
    public $Args;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Subt = $options->St;
        if($this->Options->St=='Pr')
            $this->Prop = $this->Options->Pr;
        else
            $this->Prop=ParseFactory::GetParseElement($this->Options->Pr,$this);

        $this->Caller=ParseFactory::GetParseElement($this->Options->C,$this);
        $this->Args=[];
        if(is_array($this->Options->Args))
            foreach($this->Options->Args as $arg)
                $this->Args[]=ParseFactory::GetParseElement($arg,$this);

    }


    public function &Parse($type = null)
    {
        $null=null;
        $caller=$this->Caller->Parse();
        switch($this->Subt)
        {
            case 'Pr':
                $property=strval($this->Prop);
                if(!isset($caller->$property)&&!method_exists($caller,$property))
                    return $null;

                if(method_exists($caller,$property))
                {
                    $args=[];
                    foreach($this->Args as $arg)
                        $args[]=$arg->Parse();
                    return $caller->{$property}(...$args);
                }else
                    return $caller->$property;
            case 'Arr':
                $index=$this->Prop->Parse();
                if($index==null)
                    return $null;

                if(!is_array($caller)||$index<0||$index>=count($caller))
                    return $null;
                return $caller[$index];
        }
    }

    public function Assign($value)
    {
        if($this->Caller->GetType()=='VAR')
            $caller=&$this->Caller->Parse();
        else
            $caller=$this->Caller->Parse();
        switch($this->Subt)
        {
            case 'Arr':
                $prop=$this->Prop->Parse();
                $index=ParseUtilities::SanitizeNumber($prop,null);

                if($index==null)
                    return null;
                if(!is_array($caller)||$index<0||$index>=count($caller))
                    return null;
                $caller[$index]=$value;
                break;
            case 'Pr':
                if(count($this->Args)>0)
                    return null;

                $property=strval($this->Prop);
                if(!isset($caller->$property))
                    return null;

                $caller->$property=$value;
                break;


        }
    }
}