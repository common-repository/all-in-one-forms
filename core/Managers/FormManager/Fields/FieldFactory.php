<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use Exception;
use rednaoeasycalculationforms\core\Managers\FormManager\FBColumn;

class FieldFactory{
    /**
     * @param $column FBColumn
     * @param $fieldOptions FBFieldBaseOptions
     */
    public static function GetField($loader,$column,$fieldOptions,$entry=null)
    {
        switch ($fieldOptions->Type)
        {
            case 'html':
                return new FBHTML($loader,$column,$fieldOptions,$entry);
            case 'simpletext':
                return new FBSimpleText($loader,$column,$fieldOptions,$entry);

            case 'colorpicker':
                return new FBColorPicker($loader,$column,$fieldOptions,$entry);
            case 'slider':
            case 'numeric':
                return new FBNumberField($loader,$column,$fieldOptions,$entry);
            case 'rating':
                return new FBRatingField($loader,$column,$fieldOptions,$entry);
            case 'email':
                return new FBEmail($loader, $column,$fieldOptions,$entry);
            case 'masked':
                return new FBMasked($loader, $column,$fieldOptions,$entry);
            case 'text':
                return new FBTextField($loader, $column,$fieldOptions,$entry);
            case 'website':
                return new FBWebsite($loader, $column,$fieldOptions,$entry);
            case 'password':
                return new FBPassword($loader, $column,$fieldOptions,$entry);
            case 'textarea':
                return new FBTextArea($loader, $column,$fieldOptions,$entry);
            case 'switch':
                return new FBSwitch($loader, $column,$fieldOptions,$entry);
            case 'signature':
                return new FBSignature($loader, $column,$fieldOptions,$entry);
            case 'radio':
                return new FBRadio($loader,$column,$fieldOptions,$entry);
            case 'checkbox':
                return new FBCheckBox($loader,$column,$fieldOptions,$entry);
            case 'dropdown':
                return new FBDropDown($loader,$column,$fieldOptions,$entry);
            case 'buttonselection':
                return new FBButtonSelection($loader,$column,$fieldOptions,$entry);
            case 'colorswatcher':
                return new FBColorSwatcher($loader,$column,$fieldOptions,$entry);
            case 'paragraph':
            case 'divider':
            case 'submit_button':
            case 'image':
                return new FBNoneField($loader,$column,$fieldOptions,$entry);
            case 'termofservice':
                return new FBTermOfService($loader,$column,$fieldOptions,$entry);
            case 'total':
                return new FBTotalField($loader,$column,$fieldOptions,$entry);
            case 'googlemaps':
                return new FBGoogleMaps($loader, $column,$fieldOptions,$entry);
            case 'textualimage':
                return new FBTextualImageField($loader,$column,$fieldOptions,$entry);
            case 'datepicker':
                return new FBDatePicker($loader,$column,$fieldOptions,$entry);
            case 'name':
                return new FBNameField($loader,$column,$fieldOptions,$entry);
            case 'address':
                return new FBAddressField($loader,$column,$fieldOptions,$entry);
            case 'recaptcha':
                return new FBRecaptcha($loader,$column,$fieldOptions,$entry);
            case 'actionbutton':
                return new FBActionButton($loader,$column,$fieldOptions,$entry);
            case 'survey':
                return new FBSurvey($loader,$column,$fieldOptions,$entry);
            case 'hidden':
                return new FBHiddenField($loader,$column,$fieldOptions,$entry);
            case 'list':
                return new FBListField($loader,$column,$fieldOptions,$entry);
            case 'fileupload':
                return new FBFileField($loader,$column,$fieldOptions,$entry);
            case 'grouppanel':
                return new FBGroupPanel($loader,$column,$fieldOptions,$entry);
            case 'repeater':
                return new FBRepeater($loader,$column,$fieldOptions,$entry);
            case 'imagepicker':
                return new FBImagePicker($loader,$column,$fieldOptions,$entry);
            case 'daterange':
                return new FBDateRange($loader,$column,$fieldOptions,$entry);
            case 'floatpanel':
                return new FBFloatPanel($loader,$column,$fieldOptions,$entry);
            case 'chained':
                return new FBChainedField($loader,$column,$fieldOptions,$entry);
            case 'buttoncounter':
                return new FBButtonContainer($loader,$column,$fieldOptions,$entry);

        }

        $field=null;
        $field=\apply_filters('rednao-calculated-field-get-field-by-type',$field,$loader,$fieldOptions->Type,$column,$fieldOptions,$entry);

        if($field==null)
            throw new Exception('Invalid field type '.$fieldOptions->Type);

        return $field;
    }

}