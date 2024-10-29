<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter;


class NumericComparisonFormatter extends ComparisonFormatterBase
{

    public function Format($value)
    {
        global $wpdb;
        if(!is_numeric($value))
            $value=0;
        return $wpdb->prepare('%d',$value);
    }
}