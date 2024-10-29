<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseFor_test extends TestCase{
    public function testCanUseFor(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"VAR","d":"var"},{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"NUM","d":"1"}},{"T":"FOR","V":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"NUM","d":"1"}},"C":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"10"},"St":"Lt"},"I":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"1"},"St":"Add"}},"S":{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"VAR","d":"a"},"St":"Add"}}},{"T":"VAR","d":"a"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(46,$main->Parse());
    }

    public function testCanUseForWithBlocks(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"VAR","d":"var"},{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"NUM","d":"1"}},{"T":"FOR","V":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"NUM","d":"1"}},"C":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"10"},"St":"Lt"},"I":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"1"},"St":"Add"}},"S":{"T":"BLO","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"VAR","d":"a"},"St":"Add"}},{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"BE","L":{"T":"VAR","d":"a"},"R":{"T":"NUM","d":"1"},"St":"Add"}}]}},{"T":"VAR","d":"a"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(55,$main->Parse());
    }

    public function testCanUseForWithBlocks2(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"NUM","d":"1"}},{"T":"FOR","V":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"NUM","d":"1"}},"C":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"10"},"St":"Lt"},"I":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"1"},"St":"Add"}},"S":{"T":"BLO","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"VAR","d":"a"},"St":"Add"}},{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"5"},"St":"Eq"}},"Tr":{"T":"RE","d":{"T":"VAR","d":"b"}}}]}},{"T":"VAR","d":"a"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(5,$main->Parse());
    }

    public function testCanUseBreak(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"NUM","d":"1"}},{"T":"FOR","V":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"NUM","d":"1"}},"C":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"10"},"St":"Lt"},"I":{"T":"AE","As":{"T":"VAR","d":"b"},"D":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"1"},"St":"Add"}},"S":{"T":"BLO","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"VAR","d":"b"}},{"T":"IF","Con":{"T":"PE","d":{"T":"BE","L":{"T":"VAR","d":"b"},"R":{"T":"NUM","d":"5"},"St":"Eq"}},"Tr":{"T":"BR"}}]}},{"T":"VAR","d":"a"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(5,$main->Parse());
    }
}