<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseForE_test extends TestCase{
    public function testCanUseForEach(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"NUM","d":"0"}},{"T":"FE","V":"value","K":"","D":{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"BE","L":{"T":"VAR","d":"a"},"R":{"T":"VAR","d":"value"},"St":"Add"}},"E":{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"},{"T":"NUM","d":"3"}]}},{"T":"VAR","d":"a"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(6,$parser->Parse());
    }

    public function testCanUseForWithBlock(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"NUM","d":"0"}},{"T":"FE","V":"value","K":"","D":{"T":"BLO","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"BE","L":{"T":"VAR","d":"a"},"R":{"T":"VAR","d":"value"},"St":"Add"}},{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"BE","L":{"T":"VAR","d":"a"},"R":{"T":"NUM","d":"1"},"St":"Add"}}]},"E":{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"},{"T":"NUM","d":"3"}]}},{"T":"VAR","d":"a"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(9,$parser->Parse());
    }

    public function testCanUseForWithReturn(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"FE","V":"value","K":"","D":{"T":"BLO","d":[{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"VAR","d":"value"},"R":{"T":"NUM","d":"2"},"St":"Eq"}},"Tr":{"T":"RE","d":{"T":"STR","d":"eaea"}}},{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"VAR","d":"value"}}]},"E":{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"},{"T":"NUM","d":"3"}]}}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals('eaea',$parser->Parse());

    }

    public function testCanUseBreak(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"FE","V":"value","K":"","D":{"T":"BLO","d":[{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"VAR","d":"value"},"R":{"T":"NUM","d":"2"},"St":"Eq"}},"Tr":{"T":"BR"}},{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"VAR","d":"value"}}]},"E":{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"},{"T":"NUM","d":"3"}]}},{"T":"VAR","d":"a"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(2,$parser->Parse());

    }
}