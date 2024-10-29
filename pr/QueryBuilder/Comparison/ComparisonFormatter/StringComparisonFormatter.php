<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter;

use rednaoeasycalculationforms\Utilities\Sanitizer;

class StringComparisonFormatter extends ComparisonFormatterBase
{

    public function Format($value)
    {
        global $wpdb;
        $value=Sanitizer::SanitizeString($value);
        return $wpdb->prepare('%s',$value);
    }
}