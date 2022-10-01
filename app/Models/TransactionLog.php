<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class TransactionLog extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "transactions_log";
    protected $primaryKey = "log_id";
    protected $guarded = ['log_id'];
    protected $fillable = [
        'user_id', 'transaction_type', 'amount', 'transaction_notes'
    ];


    public static function getTransactionsLogs($paginate)
    {
        $transactionsLogs = self::query()
            ->select(
                'transactions_log.log_id',
                'transactions_log.transaction_type',
                'transactions_log.amount',
                'transactions_log.transaction_notes',
                'transactions_log.created_at',
                'users.user_name'
            )
            ->join('users','users.user_id','=','transactions_log.user_id')
            ->orderBy('transactions_log.created_at','desc')
            ->paginate($paginate);
        return $transactionsLogs;
    }

    public static function getTransactionsLogsByUserId($userId, $paginate = null)
    {
        $transactionsLog = self::query()
            ->select(
                'log_id',
                'transaction_type',
                'amount',
                'transaction_notes',
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as log_created_at')
            )
            ->orderBy('created_at','desc')
            ->where('user_id','=',$userId);

        if (is_null($paginate)){

            $transactionsLog = $transactionsLog->get();
        }
        else{
            $transactionsLog = $transactionsLog->paginate($paginate);
        }

        return $transactionsLog;
    }




}
