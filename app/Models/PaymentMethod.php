<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class PaymentMethod extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "payment_methods";
    protected $primaryKey = "payment_method_id";
    protected $guarded = ['payment_method_id'];
    protected $fillable = ['payment_method_name', 'is_active'];




    public static function getPaymentMethods($getIsActiveCol)
    {
        // $getIsActiveCol => true || false
        $paymentMethods = self::query()
            ->select(
                'payment_method_id',
                self::getValueWithSpecificLang('payment_method_name', app()->getLocale(), 'payment_method_name')
            )
            ->where('is_active','=', 1);

        if($getIsActiveCol == true){
            $paymentMethods = $paymentMethods->addSelect('is_active');
        }

        $paymentMethods = $paymentMethods->get();

        return $paymentMethods;
    }
}
