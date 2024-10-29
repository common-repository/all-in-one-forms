<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseCExpression_test extends TestCase{
    public function testCallFunction(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"FC","Fn":"RNTestFunction","Ar":[]}]},"FieldsUsed":[],"Dependencies":["RNTestFunction"]}');
        $this->assertEquals('Function Called',$parser->Parse());
    }

    public function testCallFunctionWithTwoParameters(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"FC","Fn":"RNTestFunction2","Ar":[{"T":"STR","d":"MyFunction"},{"T":"STR","d":"Called"}]}]},"FieldsUsed":[],"Dependencies":["RNTestFunction2"]}');
        $this->assertEquals('MyFunctionCalled',$parser->Parse());
    }

    public function testCanCallFunctionWithTwoParameters(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"FC","Fn":"RNTestFunction3","Ar":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"}]}]},"FieldsUsed":[],"Dependencies":["RNTestFunction3"]}');
        $this->assertEquals('Value Retrieved from Function',$parser->Parse());
    }
}