<?php
/**
 * Created by yogesh on 09 06, 2023.
 *
 */

namespace Yogesmhrj\Metrics;

class Metrics
{

    const METRIC = "M";
    const IMPERIAL = "I";

    /*
     *---------------------------------------------------------------------------------------------------
     * Length
     *---------------------------------------------------------------------------------------------------
     */
    public static $UNITS_LENGTH = ['inch','in','cm','yard','m','meter','foot'];
    public static $inchToCmRatio = 2.54;
    public static $cmToInchRatio = 0.393701;

    public static $yardToMeterRatio = 0.9144;
    public static $meterToYardRatio = 1.09361;

    public static $meterToFootRatio = 3.28084;
    public static $footToMeterRatio = 0.3048;

    /*
     *---------------------------------------------------------------------------------------------------
     * Weights
     *---------------------------------------------------------------------------------------------------
     */
    public static $UNITS_WEIGHT = ['pound','ounce','gram','lbs','oz','gm','kg'];
    public static $kgToLbsRatio = 2.20462;
    public static $lbsToKgRatio = 0.453592;

    public static $gramToOunceRatio = 0.035274;
    public static $ounceToGramRatio = 28.3495;

    /*
     *---------------------------------------------------------------------------------------------------
     * Weights per area
     *---------------------------------------------------------------------------------------------------
     */
    public static $UNITS_WEIGHTS_PER_AREA = [ "gsm","osy" ];
    public static $gsmToOsyRatio = 0.029493;
    public static $osyToGsmRatio = 33.906002;


    /**
     * Extract double numbers from the string. (i.e. decimal numbers)
     * <br>
     * If you do not require decimal numbers from the string use #extractNumbers() method instead.
     *
     * @param $string
     * @return string|string[]|null
     */
    private static function extractDouble($string)
    {
        return preg_replace("/[^0-9\.]/", '', $string);
    }

    /**
     * @deprecated
     *
     * @param $value
     * @param $currentUnit
     * @param bool $toImperial
     * @param bool $stringLiteral
     */
    private static function convert($value,$currentUnit,$toImperial = true,$stringLiteral = false){
        $currentUnit = strtolower($currentUnit);
        if(in_array($currentUnit,self::$UNITS_LENGTH)){
            return self::convertLength($value,$currentUnit,$toImperial,$stringLiteral);
        }
        else if(in_array($currentUnit,self::$UNITS_WEIGHT)){
            return self::convertWeight($value,$currentUnit,$toImperial,$stringLiteral);
        }
        else if(in_array($currentUnit,self::$UNITS_WEIGHTS_PER_AREA)){
            return self::convertWeightPerArea($value,$currentUnit,$toImperial,$stringLiteral);
        }
        else {
            return $value;
        }
    }

    private static function convertLength($value,$currentUnit,$toImperial = true,$stringLiteral = false){


    }

    private static function convertWeight($value,$currentUnit,$toImperial = true,$stringLiteral = false){


    }

    private static function convertWeightPerArea($value,$currentUnit,$toImperial = true,$stringLiteral = false){


    }


    public static function cmToInch($value,$precision = 0)
    {
        return self::calculate($value,self::$cmToInchRatio,$precision);
    }

    public static function inchToCm($value,$precision = 0)
    {
        return self::calculate($value,self::$inchToCmRatio,$precision);
    }

    public static function yardToMeter($value,$precision = 0)
    {
        return self::calculate($value,self::$yardToMeterRatio,$precision);
    }

    public static function meterToYard($value,$precision = 0)
    {
        return self::calculate($value,self::$meterToYardRatio,$precision);
    }

    public static function meterToFoot($value,$precision = 0)
    {
        return self::calculate($value,self::$meterToFootRatio,$precision);
    }

    public static function footToMeter($value,$precision = 0)
    {
        return self::calculate($value,self::$footToMeterRatio,$precision);
    }

    public static function gramToOunce($value,$precision = 0)
    {
        return self::calculate($value,self::$gramToOunceRatio,$precision);
    }

    public static function ounceToGram($value,$precision = 0)
    {
        return self::calculate($value,self::$ounceToGramRatio,$precision);
    }

    public static function kgToPound($value,$precision = 0)
    {
        return self::calculate($value,self::$kgToLbsRatio,$precision);
    }

    public static function poundToKg($value,$precision = 0)
    {
        return self::calculate($value,self::$kgToLbsRatio,$precision);
    }

    public static function gsmToOsy($value, $precision = 0)
    {
        return self::calculate($value,self::$gsmToOsyRatio,$precision);
    }

    public static function osyToGsm($value,$precision = 0)
    {
        return self::calculate($value,self::$osyToGsmRatio,$precision);
    }

    private static function calculate($valuePassed,$ratio,$precision = 0){
        $value = self::extractDouble($valuePassed);
        $multiplier = pow(10,$precision);
        if(is_numeric($value)){
            $multiplier = pow(10,$precision);

            $rawValue = $value*$ratio;

            $formattedValue = (round($rawValue*$multiplier,0)%$multiplier);

            if($formattedValue == 0){

                $result = intval(round($rawValue*$multiplier)/$multiplier);

            }else{

                $result = round($value*$ratio,$precision);

            }
            if($valuePassed < 0){
                $result = $result * -1;
            }
            return $result;
        }else{
            return $value;
        }
    }

    /**
     * Switch prices in-between amount/yard and amount/meter
     *
     * @param $price
     * @param $fromUnit
     * @param $toUnit
     * @return float|int|mixed
     */
    public static function switchMeasurementPrices($price,$fromUnit,$toUnit){

        if($price == 0){
            return 0;
        }
        if($fromUnit == $toUnit){
            return $price;
        }
        if($fromUnit == 'I') {
            return $price * self::$meterToYardRatio;
        }else{
            return $price / self::$meterToYardRatio;
        }
    }

    /**
     * @param $unit
     * @return string (M if the unit is a metric unit of measurement, I otherwise)
     */
    public static function getUnitMeasurementSystem($unit){
        switch($unit){
            case "m":
            case "M":
            case "Meter":
            case "Metre":
            case "Meters":
            case "Metres":
            case "METER":
            case "METERS":
            case "METRE":
            case "METRES":
            case "cm":
            case "CM":
                return self::METRIC;
            case "yd":
            case "yard":
            case "yards":
            case "Yard":
            case "Yards":
            case "YARD":
            case "YARDS":
            case "in":
            case "IN":
                return self::IMPERIAL;
            default:
                return self::METRIC;
        }
    }

}
