<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseAssigmentExpression_test extends TestCase{
    public function testCanAccessVariable(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"VAR","d":"variable"},"D":{"T":"STR","d":"test"}},{"T":"VAR","d":"variable"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals('test',$main->Parse());
    }

    public function testCanAccessVariablesWithType(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"VAR","d":"variable"},"D":{"T":"STR","d":"test"}},{"T":"VAR","d":"variable"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals('test',$main->Parse());
    }

    public function testCanAssignArrayItem(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"VAR","d":"a"},"D":{"T":"ARR","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"},{"T":"NUM","d":"3"}]}},{"T":"AE","As":{"T":"ME","St":"Arr","C":{"T":"VAR","d":"a"},"Pr":{"T":"NUM","d":"1"},"Args":[]},"D":{"T":"STR","d":"eaeaea"}},{"T":"ME","St":"Arr","C":{"T":"VAR","d":"a"},"Pr":{"T":"NUM","d":"1"},"Args":[]}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals('eaeaea',$main->Parse());
    }

    public function testCanAssignProperties(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"AE","As":{"T":"ME","St":"Pr","C":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"Pr":"TestProperty","Args":[]},"D":{"T":"STR","d":"asignedmtf"}},{"T":"ME","St":"Pr","C":{"T":"RNF","Id":"a","Op":{"T":"STR","d":"a"}},"Pr":"TestProperty","Args":[]}]},"FieldsUsed":["a"],"Dependencies":[]}');
        $this->assertEquals('asignedmtf',$main->Parse());
    }
}