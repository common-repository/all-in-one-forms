<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\ConditionParser;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\FieldParser;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\HorizontalRulerParser;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\ImageParser;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\ParagraphParser;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\ParseTemplate;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\QRCodeParser;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\RawHTMLParser;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\TextParser;
use rednaoeasycalculationforms\Parser\Core\DataRetriever;
use rednaoeasycalculationforms\Parser\Elements\ParseMain;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class HTMLParserFactory
{
    public static function GetParser($formBuilder,$parent,$data)
    {
        if(!isset($data->type))
            return null;
        switch ($data->type)
        {
            case 'paragraph':
                $p= new HTMLSimpleContainer($formBuilder,$parent,$data,'div');
                $p->Classes='par';
                return $p;
            case 'text':
                return new TextParser($formBuilder,$parent,$data);
            case 'image':
                return new ImageParser($formBuilder,$parent,$data);
            case 'horizontal_rule':
                return new HorizontalRulerParser($formBuilder,$parent,$data);
            case 'heading':
                $level=Sanitizer::GetStringValueFromPath($data,['attrs','level'],'');
                if($level!='')
                    return new HTMLSimpleContainer($formBuilder,$parent,$data,'h'.$level);
                return null;
            case 'hard_break':
                return new RawHTMLParser($formBuilder,$parent,null,'<br/>');
            case 'blockquote':
                return new HTMLSimpleContainer($formBuilder,$parent,$data,'blockquote');
            case 'bullet_list':
                return new HTMLSimpleContainer($formBuilder,$parent,$data,'ul');
            case 'ordered_list':
                return new HTMLSimpleContainer($formBuilder,$parent,$data,'ol');
            case 'list_item':
                return new HTMLSimpleContainer($formBuilder,$parent,$data,'li');
            case 'table':
                return new HTMLSimpleContainer($formBuilder,$parent,$data,'table');
            case 'table_row':
                return new HTMLSimpleContainer($formBuilder,$parent,$data,'tr');
            case 'table_cell':
                return new HTMLSimpleContainer($formBuilder,$parent,$data,'td');
            case 'field':
                return new FieldParser($formBuilder,$parent,$data);
            case 'template':
                return new ParseTemplate($formBuilder,$parent,$data);
            case 'condition':
                return new ConditionParser($formBuilder,$parent,$data);
            case 'formula':
                if($formBuilder->IsTest)
                    $value="[Formula]";
                else {
                    try {
                        $value = new ParseMain(Sanitizer::GetValueFromPath($data, ["attrs", "formula", "Compiled"]), new DataRetriever($formBuilder));
                        $value = $value->Parse();
                    } catch (\Exception $e) {
                        $value = "";
                    }
                }
                return new RawHTMLParser($formBuilder, $parent, $data, $value);
            case 'qrcode':
                return new QRCodeParser($formBuilder,$parent,$data);

            default:
                $parser=null;
                $parser=apply_filters('allinoneforms_get_html_parser',$parser,$data,$formBuilder,$parent);
                if($parser==null)
                    throw new \Exception('Unknown type '.$data->type);
                return $parser;
        }
    }
}