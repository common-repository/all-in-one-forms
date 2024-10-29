<?php


namespace rednaoeasycalculationforms\core\Utils;


class IdUtils
{
    public static function GetUniqueId()
    {
        $id=bin2hex(openssl_random_pseudo_bytes(14));
        $id.=\uniqid();

        return $id;


    }

}