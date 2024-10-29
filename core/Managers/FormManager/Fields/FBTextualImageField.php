<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use finfo;
use rednaoeasycalculationforms\core\Integration\FileIntegration;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;
use rednaoeasycalculationforms\core\Utils\IdUtils;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBTextualImageField extends FBFieldWithPrice
{


    public function GetItems()
    {
        $value=$this->GetEntryValue('Value',array());
        $image='';
        $texts=[];

        if(Sanitizer::PathExist($value,['Image','URL']))
            $image=$value->Image->URL;

        if(isset($value->Texts))
            $texts=$value->Texts;

        return [
            'Image'=>$image,
            'Texts'=>$texts
        ];

    }

    public function GetHTMLTemplate($context = null)
    {
        return 'core/Managers/FormManager/Fields/FBTextualImageField.twig';
    }


    public function InternalToText()
    {
        $text=[];
        foreach($this->Entry->Value->Texts as $textItem)
        {
            $currentLabel='';
            if(trim($textItem->Label)!='')
                $currentLabel.=$textItem->Label.': ';

            $text[]=$currentLabel.$textItem->Value;
        }

        return \implode(', ',$text);
    }

    public function GetLineItems()
    {

        $item= parent::GetLineItems()[0];
        $value=$this->GetValue();
        if($value==null)
        {
            return [];
        }

        $itemList=array();
        foreach($value->Texts as $currentText)
        {
            $cloned=$item->CloneItem();
            $cloned->Value=$currentText->Value;
            $cloned->ExValue1=$currentText->Label;
            $itemList[]=$cloned;
        }
        return $itemList;
    }


}