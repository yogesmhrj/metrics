<?php
/**
 * Created by yogesh on 09 06, 2023.
 *
 */

namespace Yogesmhrj\Metrics;


class WeightHelper
{

    /**
     * Calculates the weight from the provided GSm and With.
     *
     * @param $gsm
     * @param $widthInCm
     * @param int $precision
     * @return float|int|string
     */
    public static function weightPerMeterFromGsm($gsm,$widthInCm,$precision = true){

        if($widthInCm == 0 || $widthInCm < 0){
            $widthInCm = 100;
        }

        $totalWeightPerMeter = ($widthInCm / 100) * $gsm;

        if($precision) {
            return number_format($totalWeightPerMeter / 1000, 4);
        }else{
            return $totalWeightPerMeter / 1000;
        }

    }

    /**
     * Calculates the weight of the product per meter in Kilograms.
     * <br>
     * The product width must be defined in cm.
     *
     * @param $product
     * @param bool $precision
     * @return float|int
     */
    public static function calculateWeightPerMeter($product, $precision = true)
    {
        $gsm = $product->meta ? $product->meta->product_weight : 1;

        $widthInCm = $product->meta ? $product->meta->product_width : 100;

        return self::weightPerMeterFromGsm($gsm,$widthInCm);
    }

    /**
     * Calculates the weight of the product per yard in Kilograms.
     * <br>
     * The product width must be defined in cm.
     *
     * @param $product
     * @param bool $precision
     * @return float|int
     */
    public static function calculateWeightPerYard($product, $precision = true)
    {
        $gmWeightPerMeter = self::calculateWeightPerMeter($product,false) * 1000;

        $totalWeightPerYard = $gmWeightPerMeter / Metrics::$meterToYardRatio;

        if($precision) {
            return number_format($totalWeightPerYard/1000,2);
        }else{
            return $totalWeightPerYard/1000;
        }
    }
}
