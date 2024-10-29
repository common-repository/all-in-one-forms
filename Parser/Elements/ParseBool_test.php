<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseBool_test extends TestCase{
    public function testCanUseBool(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BL","d":true}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$parser->Parse());

        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BL","d":false}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(false,$parser->Parse());
    }
}