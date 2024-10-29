<?php


namespace rednaoeasycalculationforms\DTO\core\Factories;


use rednaoeasycalculationforms\DTO\ConfirmationTypeEnumDTO;
use rednaoeasycalculationforms\DTO\MessageConfirmationItemOptionsDTO;
use rednaoeasycalculationforms\DTO\PageConfirmationItemOptionsDTO;
use rednaoeasycalculationforms\DTO\URLConfirmationItemOptionsDTO;

class ConfirmationItemFactory
{
    public static function GetConfirmationItem($value)
    {
        $items = [];
        if (!is_array($value))
            return [];
        foreach ($value as $confirmationItem) {
            switch ($confirmationItem->ConfirmationType) {
                case ConfirmationTypeEnumDTO::$Message:
                    $items[] = (new MessageConfirmationItemOptionsDTO())->Merge($confirmationItem);
                    break;
                case ConfirmationTypeEnumDTO::$Page:
                    $items[] = (new PageConfirmationItemOptionsDTO())->Merge($confirmationItem);
                    break;
                case ConfirmationTypeEnumDTO::$URL:
                    $items[] = (new URLConfirmationItemOptionsDTO())->Merge($confirmationItem);
                    break;
            }

        }

        return $items;

    }
}