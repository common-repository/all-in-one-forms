<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseNull_test extends TestCase{
    public function testCanUseNull(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"Null","d":null}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(null,$main->Parse());
    }
}