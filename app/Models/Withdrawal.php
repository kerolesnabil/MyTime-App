<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $table='withdrawal_requests';
    protected $primaryKey='withdrawal_id';

    protected $fillable=[
        'user_id','payment_method_id','withdrawal_amount','withdrawal_status','notes'
    ];

    public static function createWithdrawal($data)
    {
        return self::create([
            'user_id'=>$data['user_id'],
            'payment_method_id'=>$data['payment_id'],
            'withdrawal_amount'=>$data['amount']
        ]);
    }


}
