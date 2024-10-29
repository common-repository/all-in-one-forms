<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\ArraySanitizer;

class FBColorSwatcher extends FBMultipleOptionsField
{
    public function GetLineItems()
    {
        $item= parent::GetLineItems()[0];

        $options=$this->GetSelectedOptions();

        $itemList=array();
        foreach($options as $currentOption)
        {
            $newItem=$item->CloneItem();
            $newItem->Value=$currentOption->Label;
            $newItem->ExValue1=$currentOption->Color;
            $itemList[]=$newItem;
        }

        return $itemList;
    }

    public function GetHTMLTemplate($context = null)
    {
        return 'core/Managers/FormManager/Fields/FBColorSwatcher.twig';
    }

    protected function CanSanitize()
    {
        return true;
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        /** @var ArraySanitizer $item */
        $item=$sanitizer->GetSanitizerByProperty('SelectedValues');
        if($item!=null)
        {
            $item->Manager->AddStringSanitizer('Color');
        }
    }


}