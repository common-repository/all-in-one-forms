<?php

namespace rednaoeasycalculationforms\core\FieldsDictionary;



use rednaoeasycalculationforms\core\Utils\ArrayUtils;

class FieldsDictionary
{
    /** @var IFieldItem[] */
    private static $Fields=null;


    public static function CreateDictionary()
    {
        self::$Fields=[
            (object)["Name"=>"ChainedSelectField","HasStyles"=>true],
            (object)["Name"=>"WebsiteField","HasStyles"=>false],
            (object)["Name"=>"TextField","HasStyles"=>false],
            (object)["Name"=>"PasswordField","HasStyles"=>false],
            (object)["Name"=>"DropdownField","HasStyles"=>false],
            (object)["Name"=>"CheckBoxField","HasStyles"=>true],
            (object)["Name"=>"RadioField","HasStyles"=>true],
            (object)["Name"=>"SimpleTextField","HasStyles"=>true],
            (object)["Name"=>"ImageField","HasStyles"=>false],
            (object)["Name"=>"DividerField","HasStyles"=>true],
            (object)["Name"=>"NameField","HasStyles"=>false],
            (object)["Name"=>"AddressField","HasStyles"=>false],
            (object)["Name"=>"EmailField","HasStyles"=>false],
            (object)["Name"=>"NumericField","HasStyles"=>false],
            (object)["Name"=>"RatingField","HasStyles"=>false],
            (object)["Name"=>"TextAreaField","HasStyles"=>true],
            (object)["Name"=>"SwitchField","HasStyles"=>true],
            (object)["Name"=>"ButtonSelectionField","HasStyles"=>true],
            (object)["Name"=>"HTMLField","HasStyles"=>true],
            (object)["Name"=>"ActionField","HasStyles"=>false],
            (object)["Name"=>"MaskedField","HasStyles"=>false],
            (object)["Name"=>"DatePickerField","HasStyles"=>false],
            (object)["Name"=>"DateRangeField","HasStyles"=>false],
            (object)["Name"=>"SliderField","HasStyles"=>false],
            (object)["Name"=>"SignatureField","HasStyles"=>true],
            (object)["Name"=>"ColorPickerField","HasStyles"=>true],
            (object)["Name"=>"ColorSwatcherField","HasStyles"=>true],
            (object)["Name"=>"TermOfServiceField","HasStyles"=>false],
            (object)["Name"=>"ListField","HasStyles"=>true],
            (object)["Name"=>"HiddenField","HasStyles"=>false],
            (object)["Name"=>"ImageWithTextField","HasStyles"=>false],
            (object)["Name"=>"SurveyField","HasStyles"=>true],
            (object)["Name"=>"FileUploadField","HasStyles"=>true,"HasTranslations"=>true],
            (object)["Name"=>"ImagePickerField","HasStyles"=>true],
            (object)["Name"=>"GroupField","HasStyles"=>true],
            (object)["Name"=>"RepeaterField","HasStyles"=>true],
            (object)["Name"=>"FloatField","HasStyles"=>true],
            (object)["Name"=>"TotalField","HasStyles"=>true],
            (object)["Name"=>"RecaptchaField",'HasStyles'=>false],
            (object)["Name"=>"GoogleMapsField",'HasStyles'=>false],
            (object)["Name"=>"ButtonCounterField",'HasStyles'=>false]
        ];

    }


    /**
     * @return IFieldItem[]
     */
    public static function GetFields(){
        if(self::$Fields==null)
            self::CreateDictionary();
        return FieldsDictionary::$Fields;
    }

    /**
     * @param $name
     * @return IFieldItem
     */
    public static function GetFieldByName($name)
    {
        return ArrayUtils::Find(self::GetFields(),function ($item)use($name){
            return $item->Name==$name;
        });
    }


}