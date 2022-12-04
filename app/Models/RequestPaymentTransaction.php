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
    protected $primaryKey = "log_id";
    protected $guarded = ['log_id'];
    protected $fillable = [
        'user_id', 'order_id', 'request_type', 'amount',
        'request_headers', 'request_body'
    ];


    public static function createRequestPaymentTransaction($data)
    {
        self::create([
            'user_id'         => $data['user_id'],
            'order_id'        => $data['order_id'],
            'request_type'    => $data['request_type'],
            'amount'          => $data['amount'],
            'request_headers' => $data['request_headers'],
            'request_body'    => $data['request_body'],
        ]);

    }






}





