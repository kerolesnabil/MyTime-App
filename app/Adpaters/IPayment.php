<?php

namespace App\Adpaters;

interface IPayment
{


    public function createPayment($data);

    public function getPaymentInfo($paymentId);

    //public function refundPayment($paymentId);


}
