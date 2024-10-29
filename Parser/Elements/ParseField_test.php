<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseField_test extends TestCase{
    public function testCanGetFields(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $result=$main->Parse();
        $this->assertEquals(10,$result);
    }

}