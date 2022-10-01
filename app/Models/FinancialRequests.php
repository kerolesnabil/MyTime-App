<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class FinancialRequests extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "financial_requests";
    protected $primaryKey = "f_t_id";
    protected $guarded = ['f_t_id'];
    protected $fillable = [
        'user_id', 'payment_method_id', 'transaction_type',
        'amount', 'status', 'notes'
    ];


    public static function getFinancialRequestsWithType($requestType)
    {
        $financialRequests = self::query()
            ->select(
                'financial_requests.f_t_id',
                'financial_requests.transaction_type',
                'financial_requests.amount',
                'financial_requests.status',
                'financial_requests.notes',
                'financial_requests.created_at',
                'users.user_name',
                self::getValueWithSpecificLang('payment_methods.payment_method_name', app()->getLocale(), 'payment_method_name')

            )
            ->join('users','users.user_id','=','financial_requests.user_id')
            ->join('payment_methods','payment_methods.payment_method_id','=','financial_requests.payment_method_id')
            ->where('financial_requests.transaction_type','=', $requestType)
            ->orderBy('financial_requests.created_at','desc')
            ->get();
        return $financialRequests;
    }

    public static function getTransactionsLogsByUserId($userId, $paginate)
    {
        $transactionsLog = self::query()
            ->select(
                'log_id',
                'transaction_type',
                'amount',
                'status',
                'transaction_notes',
                'created_at'
            )
            ->orderBy('created_at','desc')
            ->where('user_id','=',$userId)
            ->paginate($paginate);
        return $transactionsLog;
    }


    public static function createFinancialRequest($data)
    {
        return self::create([
            'user_id'           => $data['user_id'],
            'payment_method_id' => $data['payment_id'],
            'amount'            => $data['amount'],
            'transaction_type'  => $data['transaction_type'],
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }


}
