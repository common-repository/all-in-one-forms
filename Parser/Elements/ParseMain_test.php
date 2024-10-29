<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseMain_test extends TestCase{
    public function testCanExecuteSentence(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"NUM","d":"1"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(1,$parser->Parse());
    }

    public function testMultipleSentenceReturnLastOne(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"NUM","d":"1"},{"T":"NUM","d":"2"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(2,$parser->Parse());
    }

    public function testMultipleSentencesWithAReturnGetsTheReturnOne(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"RE","d":{"T":"NUM","d":"1"}},{"T":"NUM","d":"2"}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(1,$parser->Parse());

    }


}