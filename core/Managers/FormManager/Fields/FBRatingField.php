<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBRatingField extends FBTextField
{
    public function GetLineItems()
    {
        $items= parent::GetLineItems();
        $items[0]->NumericValue=Sanitizer::SanitizeNumber($this->GetEntryValue('Value'));
        $items[0]->NumericValue2=Sanitizer::SanitizeNumber($this->GetEntryValue('NumberOfItems'));
        return $items;
    }

    public function GetText()
    {
        return $this->Entry->Value.'/'.$this->Entry->NumberOfItems;
    }

    public function GetValue(){
        return Sanitizer::GetValueFromPath($this->Entry,["Value"],null);
    }

    public function GetNumberOfItems(){
        return Sanitizer::GetValueFromPath($this->Entry,["NumberOfItems"],0);
    }

    public function GetNumberOfFullStars(){
        return floor($this->GetValue());
    }

    public function GetFullStarURL(){
        return $this->GetForm()->Loader->URL.'images/rating/star.png';
    }

    public function ShouldShowHalfStar(){
        return $this->Entry->Value-floor($this->Entry->Value)>0;
    }

    public function GetHalfStarURL(){
        return $this->GetForm()->Loader->URL.'images/rating/star-half.png';
    }

    public function GetHTMLTemplate($context=null)
    {
        return 'core/Managers/FormManager/Fields/FBRatingField.twig';
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        $sanitizer->RemoveSanitizer('Value');
        $sanitizer->AddNumericSanitizer('Value');
        $sanitizer->AddNumericSanitizer('NumberOfItems');
    }


}