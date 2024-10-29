<?php
namespace rednaoeasycalculationforms\Parser\Elements\Scalars;


use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParserElementBase;

class ParseField extends ParserElementBase{

    public $FieldId;
    /** @var FBFieldBase */
    public $Field;
    public $Method;
    /** @var ParserElementBase[] */
    public $Args;


    function __construct($Parent, $Data) {
        parent::__construct($Parent, $Data);
        $this->FieldId=$this->Data->Id;
        $this->Method=$Data->Method;
        $this->Args=[];
        if($Data->Args!=null&&isset($Data->Args))
        {
            foreach($Data->Args as $current)
                $this->Args[]=ParseFactory::GetParseElement($this,$current);
        }
        $self=$this;
        $main=$this->GetMain();
        $this->Field=array_filter($main->FieldList,function($element) use($self){
            return $element->Options->Id==$self->FieldId;
        });


        if(count($this->Field)==0)
            $this->Field=null;
        else
            $this->Field=current($this->Field);

    }

    function Parse() {
        if($this->Field==null)
            return 0;

        if($this->Method!='')
        {
            $methodToUse=$this->Method;
            $getMethodToUse='Get'.$methodToUse;
            if(!method_exists($this->Field,$methodToUse)&&\method_exists($this->Field,$getMethodToUse))
                $methodToUse='Get'.$methodToUse;

            $parsedArgs=array();

            foreach($this->Args as $current)
                $parsedArgs[]=$current->Parse();

            return \call_user_func_array(array($this->Field,$methodToUse),$parsedArgs);
        }

        return $this->Field;
    }


}