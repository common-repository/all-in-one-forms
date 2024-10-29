<?php


namespace rednaoeasycalculationforms\DTO\core;



use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class StoreBase
{

    private $defaultValuesLoaded=false;
    /** @var array */
    protected $TypeDictionary=[];
    public function LoadDefaultValues()
    {


    }

    public function GetIsStoreBase()
    {
        return true;
    }


    public function AddType($property,$type){
        $this->TypeDictionary[$property]=$type;
    }

    /**
     * @param null $itemToLoad
     * @return $this
     * @throws FriendlyException
     */
    public function Merge($itemToLoad=null){
        if(!$this->defaultValuesLoaded)
        {
            $this->LoadDefaultValues();
            $this->defaultValuesLoaded=true;
        }

        if($itemToLoad==null||(!is_object($itemToLoad)&&!is_array($itemToLoad)))
            return $this;

        foreach($itemToLoad as $key=>$value)
        {
            if(property_exists($this,$key))
            {
                $this->ParseAndSetValue($key,$value);
            }
        }
        return $this;
    }

    public function GetValueFromLoader($property, $value)
    {
        return null;
    }

    private function ParseAndSetValue($propertyName,$value)
    {
        $currentValue=$this->$propertyName;
        $loaderValue=$this->GetValueFromLoader($propertyName,$value);
        if($loaderValue!=null)
        {
            $this->$propertyName=$loaderValue;
            return;
        }

        if($currentValue!=null&&is_object($currentValue)&&$currentValue instanceof StoreBase)
        {
            if($value!=null&&is_object($value)&&$value instanceof StoreBase)
                $currentValue=$value;
            else
                $currentValue->Merge($value);
            return;
        }



        if($currentValue==null&& Sanitizer::GetStringValueFromPath($this->TypeDictionary,[$propertyName])=='Object')
        {
            $this->$propertyName=$value;
            return;
        }

        if(is_int($currentValue)||is_float($currentValue)) {
            $this->$propertyName = Sanitizer::SanitizeNumber($value);
            return;
        }

        if(is_bool($currentValue)) {
            $this->$propertyName = Sanitizer::SanitizeBoolean($value);
            return;
        }

        if(is_string($currentValue)) {
            $this->$propertyName = Sanitizer::SanitizeString($value);
            return;
        }

        if(is_array($currentValue))
        {
            if(!isset($this->TypeDictionary[$propertyName]))
                throw new FriendlyException('Could not parse property '.$propertyName);

            $type=$this->TypeDictionary[$propertyName];
            $this->$propertyName=[];
            if($type=='String'||$type=='Object')
                $this->$propertyName=$value;

            if($type=='Number'||$type=='Numeric')
            {
                $this->$propertyName =[];
                if(is_array($value))
                    foreach($value as $currentValue)
                        $this->{$propertyName}[] = Sanitizer::SanitizeNumber($currentValue);
                return;
            }

            if($type=='String')
            {
                $this->$propertyName =[];
                if(is_array($value))
                    foreach($value as $currentValue)
                        $this->{$propertyName}[] = Sanitizer::SanitizeString($currentValue);
                return;
            }

            foreach($value as $newValueItem)
            {
                if($newValueItem!=null&&is_object($newValueItem)&&$newValueItem instanceof StoreBase)
                {
                    $this->{$propertyName}[]=$newValueItem;
                }else{
                    if(strpos($type,'\\')!==0) {
                        if (strpos($type, '\\') === false)
                            $type = 'DTO\\' . $type;
                        $typeToUse = '\\rednaoeasycalculationforms\\' . $type;
                    }else
                        $typeToUse=$type;
                    $obj=new $typeToUse;
                    $this->{$propertyName}[]=$obj->Merge($newValueItem);

                }
            }

            return;

        }


        if(!isset($this->TypeDictionary[$propertyName]))
            if($value==null)
                return null;
            else
                throw new FriendlyException('Could not parse property '.$propertyName);
        else
        {
            $type=$this->TypeDictionary[$propertyName];
            if($type=='Object'||$value==null) {
                $this->$propertyName = $value;
                return;
            }

            if($type=='Number'||$type=='Numeric')
            {
                $this->{$propertyName} = Sanitizer::SanitizeNumber($currentValue);
                return;
            }

            if($type=='String')
            {
                $this->{$propertyName} = Sanitizer::SanitizeString($currentValue);
                return;
            }

            if(strpos($type,'\\')!==0) {
                if (strpos($type, '\\') === false)
                    $type = 'DTO\\' . $type;
                $typeToUse = '\\rednaoeasycalculationforms\\' . $type;
            }else
                $typeToUse=$type;
            $obj=new $typeToUse;

            $this->{$propertyName}=$obj->Merge($value);
        }






    }
}