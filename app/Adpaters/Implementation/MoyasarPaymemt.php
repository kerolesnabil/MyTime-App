<?php

namespace App\Adpaters\Implementation;

use App\Adpaters\IPayment;
use Moyasar\Moyasar;
use Moyasar\Providers\InvoiceService;

class MoyasarPaymemt implements IPayment
{

    public function __construct()
    {
        Moyasar::setApiKey('sk_test_dznBr6qi3DT4cvN2dVJVKwSJXdxHD1amgwucFjt5');
    }

    public function createPayment($data)
    {

        $invoiceService = new InvoiceService();

        // @TODO Mass3ood => ask moyasar in meta data

        $invoiceService = $invoiceService->create([
            'amount'               => $data['amount'],
            'currency'             => $data['currency'],
            'description'          => $data['description'],
            'callback_url'         => $data['callback_url'],
        ]);



        return $invoiceService->url;

    }


    public function refundOrderMoney($paymentId)
    {

        $paymentService = new \Moyasar\Providers\PaymentService();
        $payment = $paymentService->fetch("6ef68758-bdfb-44b4-b984-1688a3350884");

        dd($payment);
        $payment->refund();

    }


    public function getPaymentInfo($paymentId)
    {

        $paymentService = new \Moyasar\Providers\PaymentService();

        $payment = $paymentService->fetch($paymentId);

        dd($payment);

        return [
            "order_id"       => "",
            "user_id"        => "",
            "paymeny_status" => "",
            "money_amount"   => "",
        ];

    }
}
