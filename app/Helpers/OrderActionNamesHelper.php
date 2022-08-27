<?php


namespace App\Helpers;


class OrderActionNamesHelper
{


    public static function getOrdersStatus($ordersOrOrder, $userId = null, $vendorId = null)
    {

        $newOrderList = [];


        foreach ($ordersOrOrder as $key => $order) {

            $newOrderList[$key] = self::checkStatusOfOrder($order, $userId, $vendorId);
        }

        return $newOrderList;

    }

    private static function checkStatusOfOrder($order, $userId = null, $vendorId = null)
    {
        $actions = [];

        if ($order['order_status'] != null) {

            $statusOfOrder    = 'order_' . strtolower($order['order_status']);
            $actions          = self::$statusOfOrder($actions, $userId, $vendorId);
            $order['actions'] = $actions;
        }

        return $order;
    }

    private function order_pending($actions, $userId, $vendorId)
    {

        if (!is_null($userId)) {
            $actions = ['cancel'];
        }

        if (!is_null($vendorId)) {
            $actions = ['accept', 'reject', 'reschedule'];
        }
        return $actions;
    }


    private function order_accepted($actions, $userId, $vendorId)
    {

        if (!is_null($userId)) {
            $actions = ['change payment method', 'pay online'];
        }

        if (!is_null($vendorId)) {
            $actions = ['make order done'];
        }
        return $actions;
    }

    private function order_done($actions, $userId, $vendorId)
    {

        if (!is_null($userId)) {
            $actions = ['review order'];
        }

        return $actions;
    }

    private function order_reschedule($actions, $userId)
    {

        if (!is_null($userId)) {
            $actions = ['suggested dates'];
        }

        return $actions;
    }

    private function order_canceled($actions)
    {
        return $actions;
    }

    private function order_rejected($actions)
    {
        return $actions;
    }


}
