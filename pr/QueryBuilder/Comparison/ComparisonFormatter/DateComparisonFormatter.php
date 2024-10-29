<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter;


class DateComparisonFormatter extends ComparisonFormatterBase
{

    public function Format($value)
    {
        if(!\is_numeric($value))
            $value=0;

        global $wpdb;
        return  $wpdb->prepare('%s',date('c',$value));
    }
}