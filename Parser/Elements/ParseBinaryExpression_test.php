<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseBinaryExpression_test extends TestCase{
    public function testCanExecuteBasicAddition(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"1"},"St":"Add"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(2,$main->Parse());
    }

    public function testCanExecuteSubtraction(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"5"},"R":{"T":"NUM","d":"2"},"St":"Sub"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(3,$main->Parse());
    }

    public function testCanExecuteMultiplication(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"5"},"R":{"T":"NUM","d":"2"},"St":"Mult"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(10,$main->Parse());
    }

    public function testCanExecuteDivision(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"10"},"R":{"T":"NUM","d":"2"},"St":"Div"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(5,$main->Parse());
    }

    public function testCanDivideByZero(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"10"},"R":{"T":"NUM","d":"0"},"St":"Div"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(0,$main->Parse());
    }

    public function testOperationPrecedenceWorks(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"10"},"R":{"T":"BE","L":{"T":"BE","L":{"T":"NUM","d":"10"},"R":{"T":"NUM","d":"2"},"St":"Mult"},"R":{"T":"NUM","d":"5"},"St":"Div"},"St":"Add"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(14,$main->Parse());
    }

    public function testOperationWithParenthesisWorks(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"BE","L":{"T":"PE","d":{"T":"BE","L":{"T":"NUM","d":"10"},"R":{"T":"NUM","d":"10"},"St":"Add"}},"R":{"T":"NUM","d":"2"},"St":"Mult"},"R":{"T":"NUM","d":"5"},"St":"Div"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(8,$main->Parse());
    }

    public function testCanExecuteOperationWithOneField(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"R":{"T":"NUM","d":"10"},"St":"Add"}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals(20,$main->Parse());
    }


    public function testCanExecuteOperationWithTwoField(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"R":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"St":"Add"}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals(20,$main->Parse());
    }

    public function testCanExecuteEqualComparison(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"1"},"St":"Eq"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"2"},"St":"Eq"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(false,$main->Parse());
    }


    public function testCanExecuteNotEqualComparison(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"1"},"St":"NEq"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(false,$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"2"},"St":"NEq"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());
    }

    public function testCanExecuteGreaterComparison(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"3"},"R":{"T":"NUM","d":"2"},"St":"Gt"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"3"},"R":{"T":"NUM","d":"4"},"St":"Gt"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(false,$main->Parse());
    }



    public function testCanExecuteGreaterOrEqualComparison(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"3"},"R":{"T":"NUM","d":"2"},"St":"Gte"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"3"},"R":{"T":"NUM","d":"3"},"St":"Gte"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"3"},"R":{"T":"NUM","d":"4"},"St":"Gt"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(false,$main->Parse());
    }


    public function testCanExecuteLessComparison(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"2"},"St":"Lt"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());

    }

    public function testCanExecuteLessOrEqualComparison(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"2"},"St":"Lte"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"1"},"St":"Lte"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(true,$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"NUM","d":"1"},"R":{"T":"NUM","d":"0"},"St":"Lte"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(false,$main->Parse());
    }

    public function testCanConcatenateString(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"STR","d":"abc"},"R":{"T":"STR","d":"d"},"St":"Add"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals('abcd',$main->Parse());
    }

    public function testCanConcatenateWithFields(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"BE","L":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"R":{"T":"STR","d":"bcd"},"St":"Add"}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('abcd',$main->Parse());
    }


}