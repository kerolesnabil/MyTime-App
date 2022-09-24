<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table='deposits'

    protected $primaryKey='deposit_id';

    protected $fillable= [
        'user_id','payment_method_id','deposit_amount','deposit_status','notes'
    ]


    public function createDeposit($data)
    {
        return self::create([
            'user_id'=>$data['user_id'],
            'payment_method_id'=>$data['paymentId'],
            'deposit_amount'=>$data['amount']
        ]);
    }

}
