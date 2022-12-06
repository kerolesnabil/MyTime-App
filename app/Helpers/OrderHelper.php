<?php


namespace App\Helpers;


class OrderHelper
{


    public static function calculateOrderAppProfit($orderTotalCost, $appProfitPercentage)
    {
        $appProfitPercentage = floatval($appProfitPercentage) / 100;
        $orderTotalCost      = floatval($orderTotalCost);

        return ($orderTotalCost * $appProfitPercentage);
    }

}
