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
        'user_id', 'transaction_operation', 'amount', 'transaction_notes'
    ];


    public static function getTransactionsLogs($paginate, $attr = [])
    {
        $transactionsLogs = self::query()
            ->select(
                'transactions_log.log_id',
                'transactions_log.transaction_operation',
                'transactions_log.amount',
                self::getValueWithSpecificLang('transactions_log.transaction_notes', app()->getLocale(), 'transaction_notes'),
                DB::raw('DATE_FORMAT(transactions_log.created_at, "%Y-%m-%d  %H:%i") as log_created_at'),
                'users.user_name',
                'users.user_type'
            )
            ->join('users','users.user_id','=','transactions_log.user_id');


        if(isset($attr['date_from']) && !empty($attr['date_from'])){
             $attr['date_from'] =  date("Y-m-d H:i:s", strtotime($attr['date_from']));
             $transactionsLogs = $transactionsLogs->where('transactions_log.created_at', '>=', $attr['date_from']);
        }

        if(isset($attr['date_to']) && !empty($attr['date_to'])){
            $attr['date_to'] =  date("Y-m-d H:i:s", strtotime($attr['date_to']));
            $transactionsLogs = $transactionsLogs->where('transactions_log.created_at', '<=', $attr['date_to']);
        }

        if(isset($attr['transaction_operation']) && $attr['transaction_operation'] != 'all'){
            $transactionsLogs = $transactionsLogs->where('transactions_log.transaction_operation', '=', $attr['transaction_operation']);
        }

        if(isset($attr['user_id']) && intval($attr['user_id']) > 0){
            $transactionsLogs = $transactionsLogs->where('transactions_log.user_id', '=', $attr['user_id']);
        }

        $transactionsLogs = $transactionsLogs
                            ->orderBy('transactions_log.created_at','desc')
                            ->paginate($paginate);


        return $transactionsLogs;
    }

    public static function getTransactionsLogsByUserId($userId, $paginate = null)
    {
        $transactionsLog = self::query()
            ->select(
                'log_id',
                'transaction_operation',
                'amount',
                self::getValueWithSpecificLang('transactions_log.transaction_notes', app()->getLocale(), 'transaction_notes'),
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

    public static function createTransactionsLog($data)
    {
        return self::create([
            'user_id'               => $data['user_id'],
            'transaction_operation' => $data['transaction_operation'],
            'amount'                => $data['amount'],
            'transaction_notes'     => $data['transaction_notes'],
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

    }



}





