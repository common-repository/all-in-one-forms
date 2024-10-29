<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseBlock_test extends TestCase{
    public function testBlockReturnLastStatement(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BLO","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"},{"T":"NUM","d":"3"}]}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(3,$main->Parse());
    }

    public function testBlockCanWorkWithReturnStatement(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BLO","d":[{"T":"NUM","d":"1"},{"T":"RE","d":{"T":"NUM","d":"2"}},{"T":"NUM","d":"3"}]}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(2,$main->Parse());
    }

    public function testBlockCanWorkWithREturnStatement2(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"IF","Con":{"T":"PE","d":{"T":"BL","d":true}},"Tr":{"T":"BLO","d":[{"T":"NUM","d":"1"},{"T":"RE","d":{"T":"NUM","d":"2"}},{"T":"NUM","d":"3"}]}},{"T":"NUM","d":"4"},{"T":"NUM","d":"5"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(2,$main->Parse());
    }

    public function testEmptyBlockReturnNull(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BLO","d":[]}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(null,$main->Parse());
    }

    public function NestedBlockWorkWithReturn()
    {
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BLO","d":[{"T":"NUM","d":"1"},{"T":"BLO","d":[{"T":"NUM","d":"2"},{"T":"NUM","d":"3"},{"T":"NUM","d":"4"},{"T":"BLO","d":[{"T":"NUM","d":"5"},{"T":"RE","d":{"T":"NUM","d":"6"}}]},{"T":"NUM","d":"7"}]},{"T":"NUM","d":"8"}]},{"T":"NUM","d":"9"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(6,$main->Parse());
    }
}