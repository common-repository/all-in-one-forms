<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseIfStatement_test extends  TestCase{
    function testTruConditionIsExecuted(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"1"},"St":"Eq"}},"Tr":{"T":"NUM","d":"10"}}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(10,$main->Parse());
    }

    function testTruConditionWithElseIsEcecuted(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"1"},"St":"Eq"}},"Tr":{"T":"NUM","d":"10"},"Fa":{"T":"NUM","d":"20"}}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(10,$main->Parse());
    }

    function testElseConditionIsExecuted(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"1"},"St":"Gt"}},"Tr":{"T":"NUM","d":"10"},"Fa":{"T":"NUM","d":"20"}}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(20,$main->Parse());
    }

    function testCanREturnInAnIf(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"NUM","d":"2"},"R":{"T":"NUM","d":"1"},"St":"Gt"}},"Tr":{"T":"RE","d":{"T":"NUM","d":"10"}},"Fa":{"T":"NUM","d":"20"}},{"T":"NUM","d":"30"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(10,$main->Parse());

    }
}