<?php

namespace rednaoeasycalculationforms\PublicApi;

use Cassandra\FutureClose;
use rednaoeasycalculationforms\core\db\FormRepository;

class FormApi
{
    public function Get($formId,$loadServerOptions=true,$mode='raw'){
        $formRepository=new FormRepository(AllInOneForms()->GetLoader());
        $form= $formRepository->GetForm($formId,$loadServerOptions);
        if($form==null)
            return null;

        if($mode=='form')
        {
            $form->Initialize();
            return $form;
        }
        if($mode=='raw')
            return $form->Options;

        return null;
    }

    public function List(){
        $formRepository=new FormRepository(AllInOneForms()->GetLoader());
        return $formRepository->GetForms(false);
    }

}