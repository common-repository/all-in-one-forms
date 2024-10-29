<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core;

class ParserUtilities
{
    /**
     * @param $parser HTMLParserBase
     */
    public static function MaybeApplyMarks($parser){
        if(isset($parser->Data->marks)&&count($parser->Data->marks)>0)
        {
            $parser= new ParserWithMarks($parser->FormBuilder,$parser->Parent,$parser->Data,$parser);
            return $parser->ParseContent();
        }

        return $parser;
    }
}