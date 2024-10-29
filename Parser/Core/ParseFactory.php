<?php

namespace rednaoeasycalculationforms\Parser\Core;

use Exception;
use rednaoeasycalculationforms\Parser\Elements\ParseArray;
use rednaoeasycalculationforms\Parser\Elements\ParseAssigmentExpression;
use rednaoeasycalculationforms\Parser\Elements\ParseBinaryExpression;
use rednaoeasycalculationforms\Parser\Elements\ParseBlock;
use rednaoeasycalculationforms\Parser\Elements\ParseBool;
use rednaoeasycalculationforms\Parser\Elements\ParseBreak;
use rednaoeasycalculationforms\Parser\Elements\ParseCExpression;
use rednaoeasycalculationforms\Parser\Elements\ParseCondE;
use rednaoeasycalculationforms\Parser\Elements\ParseField;
use rednaoeasycalculationforms\Parser\Elements\ParseFixed;
use rednaoeasycalculationforms\Parser\Elements\ParseFor;
use rednaoeasycalculationforms\Parser\Elements\ParseForE;
use rednaoeasycalculationforms\Parser\Elements\ParseIfStatement;
use rednaoeasycalculationforms\Parser\Elements\ParseMembE;
use rednaoeasycalculationforms\Parser\Elements\ParseNull;
use rednaoeasycalculationforms\Parser\Elements\ParseNumber;
use rednaoeasycalculationforms\Parser\Elements\ParseParenthesis;
use rednaoeasycalculationforms\Parser\Elements\ParseParenthezisedExpression;
use rednaoeasycalculationforms\Parser\Elements\ParseReturn;
use rednaoeasycalculationforms\Parser\Elements\ParseString;
use rednaoeasycalculationforms\Parser\Elements\ParseUnaryExpression;
use rednaoeasycalculationforms\Parser\Elements\ParseVariable;

class ParseFactory {
    /**
     * @param $parent
     * @param $element
     * @return ParseBase
     */
    public static function GetParseElement($element,$parent)
    {
        if($element==null)
            return null;
        switch ($element->T) {
            case 'NUM':
                return new ParseNumber($element,$parent);
            case 'RE':
                return new ParseReturn($element,$parent);
            case 'RNF':
                return new ParseField($element,$parent);
            case 'RNFX':
                return new ParseFixed($element,$parent);
            case 'ARR':
                return new ParseArray($element,$parent);
            case 'ME':
                return new ParseMembE($element,$parent);
            case 'AE':
                return new ParseAssigmentExpression($element,$parent);
            case 'VAR':
                return new ParseVariable($element,$parent);
            case 'STR':
                return new ParseString($element,$parent);
            case 'BE':
                return new ParseBinaryExpression($element,$parent);
            case 'PE':
                return new ParseParenthesis($element,$parent);
            case 'BLO':
                return new ParseBlock($element,$parent);
            case "IF":
                return new ParseIfStatement($element,$parent);
            case "BL":
                return new ParseBool($element,$parent);
            case "BR":
                return new ParseBreak($element,$parent);
            case "CE":
                return new ParseCondE($element,$parent);
            case 'FC':
                return new ParseCExpression($element,$parent);
            case 'FOR':
                return new ParseFor($element,$parent);
            case "FE":
                return new ParseForE($element,$parent);
            case 'Null':
                return new ParseNull($element,$parent);
            case 'UE':
                return new ParseUnaryExpression($element,$parent);
            default:
                throw new Exception('Invalid token '.$element->T);



        }
    }
}