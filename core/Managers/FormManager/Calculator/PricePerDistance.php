<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


class PricePerDistance extends CalculatorBase
{

    public $EarthRadius=6378137;
    public function ExecutedCalculation($value)
    {
        if($value==null)
            $value=$this->Field->GetValue();


        $origins=$this->Field->GetOptionValue('Origins',array());
        if(count($origins)==0)
            return $this->CreateCalculationObject('','',0);

        $latitude1=$origins[0]->Latitude;
        $longitude1=$origins[0]->Longitude;

        $latitude2=$value->Latitude;
        $longitude2=$value->Longitude;


        $distance=$this->ComputeDistanceBetween(array('lat'=>$latitude1,'lng'=>$longitude1),array('lat'=>$latitude2,'lng'=>$longitude2));
        $distance=\round($distance,8);
        $regularPriceToUse=$this->Field->GetRegularPrice();

        switch ($this->Field->GetOptionValue('RoundingType',''))
        {
            case 'round_up':
                $distance=\ceil($distance);
                break;
            case 'round_down':
                $distance=floor($distance);
                break;
        }


        $price=$regularPriceToUse;

        $price=\floatval($price);
        $measureFactor=1;

        switch ($this->Field->GetOptionValue('MeasureType',''))
        {
            case "kilometer":
                $measureFactor=.001;
                break;
            case 'miles':
                $measureFactor=.00062137273;
                break;
        }

        return $this->CreateCalculationObject($distance*$measureFactor*$price,0,1);
    }



    public function ComputeDistanceBetween( $from, $to) {
        return $this->ComputeAngleBetween($from, $to) * $this->EarthRadius;
    }

    public function ComputeAngleBetween($from, $to) {
        return $this->DistanceRadians(deg2rad($from['lat']), deg2rad($from['lng']),
        deg2rad($to['lat']), deg2rad($to['lng']));
    }

    public function DistanceRadians( $lat1,  $lng1,  $lat2,  $lng2) {
        return $this->arcHav($this->HavDistance($lat1, $lat2, $lng1 - $lng2));
    }

    function Hav($x) {
        $sinHalf = sin($x * 0.5);
        return $sinHalf * $sinHalf;
    }

    function HavDistance($lat1, $lat2, $dLng) {
        return $this->Hav($lat1 - $lat2) + $this->Hav($dLng) * cos($lat1) * cos($lat2);
    }

    function arcHav($x) {
        return 2 * asin(sqrt($x));
    }



}