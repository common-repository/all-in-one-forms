<?php

namespace rednaoeasycalculationforms\Managers\ErrorManager;

use rednaoeasycalculationforms\core\Exception\FriendlyException;

class ErrorManager
{
    /**
     * @param $error FriendlyException
     * @return void
     */
    public static function PrintError($error){
        $twigManager=AllInOneForms()->GetLoader()->GetTwigManager();
        echo $twigManager->Render('Managers/ErrorManager/ErrorManager.twig',null,['Error'=>$error->getMessage(),'Detail'=>$error instanceof FriendlyException?$error->Details:$error->getMessage(),$error->getTraceAsString(),'ErrorImage'=>AllInOneForms()->GetLoader()->URL.'images/Error.png']);
    }
}