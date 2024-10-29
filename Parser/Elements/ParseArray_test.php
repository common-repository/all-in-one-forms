<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseArray_test extends TestCase{
    public function testCanUseArrays(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"}]}]},"FieldsUsed":[],"Dependencies":[]}');
        $result=$main->Parse();
        $this->assertEquals('1, 2',$result);
    }
}