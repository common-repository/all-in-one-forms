<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context;

use rednaoeasycalculationforms\core\Managers\LogManager\LogManager;

class HTMLEmailContext extends HTMLContextBase
{
    public $InlinedImages=[];
    public function AddInlineImage($path)
    {
        if($path==null)
            return '';
        foreach($this->InlinedImages as $id=>$filePath)
        {
            if($path==$filePath)
                return $id;
        }

        $id=uniqid();
        LogManager::LogDebug('Adding inline image '.$path.'with id '.$id);
        $this->InlinedImages[$id]=$path;
        return $id;

    }
}