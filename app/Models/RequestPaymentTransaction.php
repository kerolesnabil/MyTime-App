<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class RequestPaymentTransaction extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "request_payment_transactions";
    protected $primaryKey = "id";
    protected $guarded = ['id'];
    protected $fillable = [
        'invoice_id', 'user_id', 'order_id', 'request_type',
        'amount', 'request_headers', 'request_body'
    ];


    public static function createRequestPaymentTransaction($data)
    {
        self::create([
            'invoice_id'      => $data['invoice_id'],
            'user_id'         => $data['user_id'],
            'order_id'        => $data['order_id'],
            'request_type'    => $data['request_type'],
            'amount'          => $data['amount'],
            'request_headers' => !isset($data['request_headers']) ?  null : $data['request_headers'],
            'request_body'    => !isset($data['request_body']) ?  null : $data['request_body'],
        ]);

    }

    public static function getRequestPaymentTransactionByInvoiceId($invoiceId)
    {
        return self::query()
            ->select(
                'id',
                'invoice_id',
                'user_id',
                'order_id',
                'request_type',
                'amount',
                'request_headers',
                'request_body'
            )
            ->where('invoice_id', $invoiceId)
            ->first();
    }

    public static function updateRequestPaymentTransaction($requestId, $data)
    {
        self::where('id', '=', $requestId)
            ->update($data);

    }

    public static function getRequestPaymentByOrderIdAndUserId($orderId, $userId)
    {
        return self::query()
            ->select(
                'id',
                'invoice_id',
                'user_id',
                'order_id',
                'request_type',
                'amount',
                'request_headers',
                'request_body'
            )
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }

}





