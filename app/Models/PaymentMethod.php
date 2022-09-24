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
    protected $fillable = ['payment_method_name', 'payment_method_type', 'is_active'];




    public static function getPaymentMethods($apiOrWeb)
    {
        // $apiOrWeb => api || web
        $paymentMethods = self::query()
            ->select(
                'payment_method_id',
                self::getValueWithSpecificLang('payment_method_name', app()->getLocale(), 'payment_method_name')
            );


        if($apiOrWeb == 'web'){
            $paymentMethods = $paymentMethods->addSelect('is_active', 'payment_method_type');
        }
        else{
            $paymentMethods =   $paymentMethods->where('is_active','=', 1);
        }

        $paymentMethods = $paymentMethods->get();

        return $paymentMethods;
    }


    public static function getPaymentMethodById($paymentMethodId)
    {
        $paymentMethod = self::query()
            ->select(
                'payment_method_id',
                'is_active',
                'payment_method_type',
                'payment_method_name'
            )
            ->where('payment_method_id','=', $paymentMethodId)
            ->first();
        return $paymentMethod;
    }


    public static function savePaymentMethod($data, $paymentMethodId = null)
    {

        if(is_null($paymentMethodId)){

            return self::create([
                'payment_method_name' => $data['payment_method_name'],
                'payment_method_type' => $data['payment_method_type'],
                'is_active'           => $data['is_active'],
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
        else {
            return self::where('payment_method_id', '=', $paymentMethodId)
                ->update(array(
                    'payment_method_name' => $data['payment_method_name'],
                    'payment_method_type' => $data['payment_method_type'],
                    'is_active'           => $data['is_active'],
                ));
        }


    }

    public static function updatePaymentMethodActivationStatus($paymentMethodId, $status)
    {
        //$status => 0 || 1
        self::where('payment_method_id', '=', $paymentMethodId)
            ->update(array(
                'is_active'     => $status,
                'updated_at'    => now()
            ));


    }

    public static function getPaymentByMethodType($type)
    {
        return self::query()
            ->select('payment_method_id')
            ->where('payment_method_type','=',$type)
            ->first()->toArray();
    }


}
