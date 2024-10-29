<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\Parser\Core\DataRetriever;
use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParserElementBase;
use rednaoeasycalculationforms\Parser\Core\ParserElementThatUsesFieldsBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use stdClass;
use undefined\DTO\FBFieldBaseOptions;

class ParseMain extends ParseBase {

    /** @var ParseBase[] */
    public $Sentences;
    /** @var DataRetriever */
    private $_retriever;
    public $ShouldReturn=false;

    public function __construct($options,$dataRetriever)
    {
        parent::__construct($options,null);
        $this->Sentences=[];
        $this->_retriever=$dataRetriever;

        if(is_array($options->d))
        {
            foreach($options->d as $sentence)
            {
                $this->Sentences[]=ParseFactory::GetParseElement($sentence,$this);
            }
        }

    }

    public function GetRetriever()
    {
        return $this->_retriever;
    }

    protected function InternalParse(){
        $result=0;
        foreach($this->Sentences as $sentence)
        {
            $result=$sentence->Parse();
            if($this->ShouldReturn)
                break;
        }
        return $result;
    }

    public function ParsePrice(){
        return Sanitizer::SanitizeNumber($this->Parse());
    }

    public function ParseNumber(){
        return Sanitizer::SanitizeNumber($this->Parse('Num'));
    }

    public function ReturnWasExecuted(){
        $this->ShouldReturn=true;
    }

    public function Parse($type=null){
        $result=$this->InternalParse();
        if($result instanceof FBFieldBase)
        {
            if($type=='Num')
                return $result->ToNumber();

            if($type=='Str')
                return $result->ToText();

            return $result->ToNumber();
        }

        if(is_array($result))
        {
            return implode(', ',ArrayUtils::Map($result,function($item){
                return Sanitizer::SanitizeString($item);
            }));
        }

        if($result===null)
            return null;

        if(is_bool($result)||is_string($result)||is_numeric($result))
            return $result;
        return Sanitizer::SanitizeString($result);
    }

    public function ParseString(){
        return Sanitizer::SanitizeString($this->Parse('Str'));
    }

}

