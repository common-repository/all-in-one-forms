<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLEmailContext;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\SingleLineGenerator\SingleLineGenerator;

class QRCodeParser extends HTMLParserBase
{
    public $URL;
    public $Size;
    public function ParseContent()
    {
        $singleLineGenerator=new SingleLineGenerator($this->FormBuilder);
        $text=$singleLineGenerator->GetText($this->GetAttributeValue('Text'));
        $this->Size=$this->GetAttributeValue('Size');

        $context=$this->GetDocument()->Context;

        if($context!=null)
        {
            if($context instanceof HTMLEmailContext)
            {
                $path=realpath(get_temp_dir());
                $fileName=wp_unique_filename($path,'file.png');
                $path=$path.'/'.$fileName;
                Builder::create()
                    ->writer(new PngWriter())
                    ->encoding(new Encoding('UTF-8'))
                    ->size($this->Size)
                    ->data($text)
                    ->build()->saveToFile($path);
                $this->URL='cid:'.$context->AddInlineImage($path);

                return $this;
            }
        }

        $this->URL=Builder::create()
            ->writer(new PngWriter())
            ->encoding(new Encoding('UTF-8'))
            ->size($this->Size)
            ->data($text)
            ->build()->getDataUri();

        return $this;
    }

    protected function GetTemplateName()
    {
        return 'core/Managers/HTMLGenerator/HTMLParsers/QRCodeParser.twig';
    }
}