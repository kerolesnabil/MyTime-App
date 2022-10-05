<?php

namespace App\Models;


use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class FinancialRequests extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "financial_requests";
    protected $primaryKey = "f_t_id";
    protected $guarded = ['f_t_id'];
    protected $fillable = [
        'user_id', 'payment_method_id', 'request_type', 'amount',
        'status', 'iban_number', 'bank_name',
        'notes', 'withdrawal_confirmation_receipt_img', 'deposit_receipt_img'
    ];


    public static function getFinancialRequestsWithType($requestType, $attr = [])
    {
        $financialRequests = self::query()
            ->select(
                'financial_requests.f_t_id',
                'financial_requests.request_type',
                'financial_requests.amount',
                'financial_requests.status',
                'financial_requests.notes',
                'financial_requests.created_at',
                'users.user_name',
                self::getValueWithSpecificLang
                (
                    'payment_methods.payment_method_name',
                    app()->getLocale(),
                    'payment_method_name'
                )
            )
            ->join('users','users.user_id','=','financial_requests.user_id')
            ->join('payment_methods', 'payment_methods.payment_method_id', '=', 'financial_requests.payment_method_id')
            ->where('financial_requests.request_type','=', $requestType);



            if(isset($attr['date_from']) && !empty($attr['date_from'])){

                $attr['date_from'] =  date("Y-m-d H:i:s", strtotime($attr['date_from']));
                $financialRequests = $financialRequests->where('financial_requests.created_at', '>=', $attr['date_from']);
            }

            if(isset($attr['date_to']) && !empty($attr['date_to'])){

                $attr['date_to'] =  date("Y-m-d H:i:s", strtotime($attr['date_to']));
                $financialRequests = $financialRequests->where('financial_requests.created_at', '<=', $attr['date_to']);
            }

            if(isset($attr['status']) && $attr['status'] != 'all'){

                if ($attr['status'] == 'null'){
                    $attr['status'] = null;
                }
                $financialRequests = $financialRequests->where('financial_requests.status', '=', $attr['status']);
            }

            $financialRequests = $financialRequests->orderBy('financial_requests.created_at','desc')->get();
            return $financialRequests;
    }

    public static function getFinancialRequestsByUserId($userId, $requestType)
    {
        $requests = self::query()
            ->select(
                'f_t_id',
                self::getValueWithSpecificLang
                ('payment_methods.payment_method_name',
                    app()->getLocale(),
                    'payment_method_name'
                ),
                'amount',
                'status',
                'notes',
                DB::raw('DATE_FORMAT(financial_requests.created_at, "%Y-%m-%d  %H:%i") as request_created_at')
            )
            ->join
            (
                'payment_methods',
                'payment_methods.payment_method_id',
                '=',
                'financial_requests.payment_method_id'
            )
            ->orderBy('financial_requests.created_at','desc')
            ->where('user_id','=',$userId)
            ->where('request_type', '=', $requestType);


        if ($requestType == 'deposit'){

            $requests = $requests->addSelect('deposit_receipt_img');
        }
        else{

            $requests = $requests->addSelect(
                'withdrawal_confirmation_receipt_img'
            );
        }


        $requests = $requests->get();

        if (!empty($requests)){

            foreach ($requests as $request){
                if (is_null($request['status'])){
                    $request['status'] = 'waiting';
                }
                elseif ($request['status'] == 0){
                    $request['status'] = 'not_approved';
                }
                else{
                    $request['status'] = 'approved';
                }

                if (isset($request['deposit_receipt_img']) && !is_null($request['deposit_receipt_img'])) {
                    $request["deposit_receipt_img"] = ImgHelper::returnImageLink($request["deposit_receipt_img"]);
                }

                if (isset($request['withdrawal_confirmation_receipt_img']) && !is_null($request['withdrawal_confirmation_receipt_img'])) {
                    $request["withdrawal_confirmation_receipt_img"] = ImgHelper::returnImageLink($request["withdrawal_confirmation_receipt_img"]);
                }
            }
        }

        return $requests;
    }

    public static function createDepositRequest($data)
    {
        return self::create([
            'user_id'             => $data['user_id'],
            'payment_method_id'   => $data['payment_id'],
            'amount'              => $data['amount'],
            'request_type'        => $data['request_type'],
            'deposit_receipt_img' => $data['deposit_receipt_img'],
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }


    public static function createWithdrawalRequest($data)
    {
        return self::create([
            'user_id'           => $data['user_id'],
            'payment_method_id' => $data['payment_id'],
            'amount'            => $data['amount'],
            'request_type'      => $data['request_type'],
            'bank_name'         => $data['bank_name'],
            'iban_number'       => $data['iban_number'],
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }


    public static function getFinancialRequestsByRequestId($requestId)
    {
        $request = self::query()
            ->select(
                'f_t_id',
                self::getValueWithSpecificLang
                ('payment_methods.payment_method_name',
                    app()->getLocale(),
                    'payment_method_name'
                ),
                'amount',
                'request_type',
                'status',
                'notes',
                'deposit_receipt_img',
                'withdrawal_confirmation_receipt_img',
                'iban_number',
                'bank_name',
                DB::raw('DATE_FORMAT(financial_requests.created_at, "%Y-%m-%d  %H:%i") as request_created_at'),
                'users.user_id',
                'users.user_name',
                'users.user_phone',
                'users.user_email'
            )
            ->join('payment_methods', 'payment_methods.payment_method_id', '=', 'financial_requests.payment_method_id')
            ->join('users', 'users.user_id', '=', 'financial_requests.user_id')
            ->orderBy('financial_requests.created_at','desc')
            ->where('f_t_id','=',$requestId)->first();


        if (!is_null($request)){

            if (!is_null($request['deposit_receipt_img'])) {
                $request["deposit_receipt_img"] = ImgHelper::returnImageLink($request["deposit_receipt_img"]);
            }

            if (!is_null($request['withdrawal_confirmation_receipt_img'])) {
                $request["withdrawal_confirmation_receipt_img"] = ImgHelper::returnImageLink($request["withdrawal_confirmation_receipt_img"]);
            }

        }

        return $request;
    }


    public static function updateFinancialRequest($data, $requestId)
    {
        self::where('f_t_id', $requestId)->update($data);
    }

}
