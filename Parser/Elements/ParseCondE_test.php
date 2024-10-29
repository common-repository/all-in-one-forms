<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseCondE_test extends TestCase{
    public function testCanUseTruCondition(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"CE","C":{"T":"BE","L":{"T":"NUM","d":"10"},"R":{"T":"NUM","d":"9"},"St":"Gt"},"Tr":{"T":"STR","d":"one"},"Fa":{"T":"STR","d":"two"}}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals("one",$main->Parse());
    }

    public function testCanUseFalseCondition(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"CE","C":{"T":"BE","L":{"T":"NUM","d":"11"},"R":{"T":"NUM","d":"12"},"St":"Gt"},"Tr":{"T":"STR","d":"one"},"Fa":{"T":"STR","d":"two"}}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals("two",$main->Parse());
    }
}