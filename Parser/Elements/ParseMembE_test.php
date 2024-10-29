<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseMembE_test extends TestCase{
    public function testCanAccessArrayItem(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ME","St":"Arr","C":{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"},{"T":"NUM","d":"3"}]},"Pr":{"T":"NUM","d":"1"},"Args":[]}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals('2',$main->Parse());
    }

    public function testCanAccessMethodWithoutParenthesis(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ME","St":"Pr","C":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"Pr":"TestMethod","Args":[]}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('TestMethod',$main->Parse());

        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ME","St":"Pr","C":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"Pr":"TestMethod","Args":[]}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('TestMethod',$main->Parse());
    }

    public function testCanAccessProperties(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ME","St":"Pr","C":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"Pr":"TestProperty","Args":[]}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('TestPropertyCalled',$main->Parse());
    }

    public function TestAccessArrayOfFields(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ME","St":"Arr","C":{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}}]},"Pr":{"T":"NUM","d":"1"},"Args":[]}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('10',$main->Parse());
    }

    public function CanAccessMethodWithOneParameter(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ME","St":"Pr","C":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"Pr":"TestMethod2","Args":[{"T":"STR","d":"methodwithparameters"}]}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('methodwithparametersexecuted',$main->Parse());
    }

    public function CanAssessMethodsWithTwoParameters(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"ME","St":"Pr","C":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"Pr":"TestMethod3","Args":[{"T":"STR","d":"methodwithparameters"},{"T":"STR","d":"with2params"}]}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('methodwithparameters_with2params_was_executed',$main->Parse());
    }
}