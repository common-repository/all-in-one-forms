<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseUnaryExpression_test extends TestCase{
    public function testUnaryExpression(){
        $parser=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"UE","St":"Ne","d":{"T":"BL","d":true}}]},"FieldsUsed":[],"Dependencies":[]}');
        $this->assertEquals(false,$parser->Parse());
    }
}