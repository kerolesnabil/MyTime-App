<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class TransactionLog extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "transactions_log";
    protected $primaryKey = "log_id";
    protected $guarded = ['log_id'];
    protected $fillable = [
        'user_id', 'transaction_type', 'amount', 'status', 'transaction_notes'
    ];


    public static function getAllTransactionsLogs($paginate)
    {
        $transactionsLog = self::query()
            ->select(
                'transactions_log.log_id',
                'transactions_log.transaction_type',
                'transactions_log.amount',
                'transactions_log.status',
                'transactions_log.transaction_notes',
                'transactions_log.created_at',
                'users.user_name'
            )
            ->join('users','users.user_id','=','transactions_log.user_id')
            ->orderBy('transactions_log.created_at','desc')
            ->get();
        return $transactionsLog;
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




}
