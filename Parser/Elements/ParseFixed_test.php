<?php

namespace rednaoeasycalculationforms\Parser\Elements;


use PHPUnit\Framework\TestCase;
use rednaoeasycalculationforms\test\Mocks\ParseText;

class ParseFixed_test extends TestCase{

    public function testFixedFieldsCanBeUsed(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"RNFX","Id":"LastName","Op":null}]},"FieldsUsed":[],"Dependencies":[]}');
        $result=$main->Parse();
        $this->assertEquals('Smith',$result);
    }

    public function testFixedFieldsWithParametersWork(){
        $main=ParseText::ParseText('{"Compiled":{"T":"MA","d":[{"T":"RNFX","Id":"UserMeta","Op":{"Id":"test"}}]},"FieldsUsed":[],"Dependencies":[]}');
        $result=$main->Parse();
        $this->assertEquals('test_meta',$result);
    }
}