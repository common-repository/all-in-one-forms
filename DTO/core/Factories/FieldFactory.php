<?php


namespace rednaoeasycalculationforms\DTO\core\Factories;


use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\DTO\ActionFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\AddressFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ButtonCounterFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ButtonSelectionFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ChainedSelectFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\CheckBoxFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ColorPickerFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ColorSwatcherFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\DatePickerFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\DateRangeFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\DividerFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\DropdownFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\EmailFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\FileUploadFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\FloatFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\GoogleMapsFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\GroupFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\HiddenFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\HTMLFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ImageFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ImagePickerFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ImageWithTextFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\ListFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\MaskedFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\NameFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\NumericFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\PasswordFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\RadioFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\RatingFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\RecaptchaFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\RepeaterFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\RepeaterItemOptionsDTO;
use rednaoeasycalculationforms\DTO\SignatureFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\SimpleTextFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\SliderFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\SubmitButtonOptionsDTO;
use rednaoeasycalculationforms\DTO\SurveyFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\SwitchFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\TermOfServiceFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\TextAreaFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\TextFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\TotalFieldOptionsDTO;
use rednaoeasycalculationforms\DTO\WebsiteFieldOptionsDTO;

class FieldFactory
{
    public static function GetFieldOptions($value)
    {
        switch ($value->Type)
        {
            case 'text':
                return (new TextFieldOptionsDTO())->Merge($value);
            case 'submit_button':
                return (new SubmitButtonOptionsDTO())->Merge($value);
            case 'dropdown':
                return (new DropdownFieldOptionsDTO())->Merge($value);
            case 'checkbox':
                return (new CheckBoxFieldOptionsDTO())->Merge($value);
            case 'radio':
                return (new RadioFieldOptionsDTO())->Merge($value);
            case 'buttonselection':
                return (new ButtonSelectionFieldOptionsDTO())->Merge($value);
            case 'simpletext':
                return (new SimpleTextFieldOptionsDTO())->Merge($value);
            case 'html':
                return (new HTMLFieldOptionsDTO())->Merge($value);
            case 'image':
                return (new ImageFieldOptionsDTO())->Merge($value);
            case 'divider':
                return (new DividerFieldOptionsDTO())->Merge($value);
            case 'name':
                return (new NameFieldOptionsDTO())->Merge($value);
            case 'address':
                return (new AddressFieldOptionsDTO())->Merge($value);
            case 'email':
                return (new EmailFieldOptionsDTO())->Merge($value);
            case 'actionbutton':
                return (new ActionFieldOptionsDTO())->Merge($value);
            case 'numeric':
                return (new NumericFieldOptionsDTO())->Merge($value);
            case 'textarea':
                return (new TextAreaFieldOptionsDTO())->Merge($value);
            case 'masked':
                return (new MaskedFieldOptionsDTO())->Merge($value);
            case 'datepicker':
                return (new DatePickerFieldOptionsDTO())->Merge($value);
            case 'daterange':
                return (new DateRangeFieldOptionsDTO())->Merge($value);
            case 'slider':
                return (new SliderFieldOptionsDTO())->Merge($value);
            case 'switch':
                return (new SwitchFieldOptionsDTO())->Merge($value);
            case 'signature':
                return (new SignatureFieldOptionsDTO())->Merge($value);
            case 'colorpicker':
                return (new ColorPickerFieldOptionsDTO())->Merge($value);
            case 'termofservice':
                return (new TermOfServiceFieldOptionsDTO())->Merge($value);
            case 'list':
                return (new ListFieldOptionsDTO())->Merge($value);
            case 'colorswatcher':
                return (new ColorSwatcherFieldOptionsDTO())->Merge($value);
            case 'hidden':
                return (new HiddenFieldOptionsDTO())->Merge($value);
            case 'textualimage':
                return (new ImageWithTextFieldOptionsDTO())->Merge($value);
            case 'survey':
                return (new SurveyFieldOptionsDTO())->Merge($value);
            case 'fileupload';
                return (new FileUploadFieldOptionsDTO())->Merge($value);
            case 'imagepicker':
                return (new ImagePickerFieldOptionsDTO())->Merge($value);
            case 'grouppanel':
                return (new GroupFieldOptionsDTO())->Merge($value);
            case 'repeater':
                return (new RepeaterFieldOptionsDTO())->Merge($value);
            case 'repeater_item':
                return (new RepeaterItemOptionsDTO())->Merge($value);
            case 'total':
                return (new TotalFieldOptionsDTO())->Merge($value);
            case 'floatpanel':
                return (new FloatFieldOptionsDTO())->Merge($value);
            case 'recaptcha':
                return (new RecaptchaFieldOptionsDTO())->Merge($value);
            case 'googlemaps':
                return (new GoogleMapsFieldOptionsDTO())->Merge($value);
            case 'password':
                return (new PasswordFieldOptionsDTO())->Merge($value);
            case 'website':
                return (new WebsiteFieldOptionsDTO())->Merge($value);
            case 'chained':
                return (new ChainedSelectFieldOptionsDTO())->Merge($value);
            case 'rating':
                return (new RatingFieldOptionsDTO())->Merge($value);
            case 'buttoncounter':
                return (new ButtonCounterFieldOptionsDTO())->Merge($value);

            default:
                $field=apply_filters('allinoneforms_get_field_options',null, $value);
                if($field!=null)
                    return $field;
                throw new FriendlyException('Undefined field type '.$value->Type);
        }
    }
}