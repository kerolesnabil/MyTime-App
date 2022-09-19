<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey='user_id';

    public $timestamps=false;

    protected $table='users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name','user_address','user_phone','user_type',
        'user_email','user_date_of_birth','user_lat','user_long', 'user_img'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    public static function getUserByPhone($user_phone)
    {
       return self::where('user_phone',$user_phone)->first();
    }

    public static function createUser($data, $type, $image = null, $user_date_of_birth = null, $user_address=null)
    {
       $userData = self::create([
           'user_name'          => $data->user_name,
           'user_type'          => $type,
           'user_address'       => $user_address,
           'user_phone'         => $data->user_phone,
           'user_email'         => $data->user_email,
           'user_date_of_birth' => $user_date_of_birth,
           'user_lat'           => $data->user_lat,
           'user_long'          => $data->user_long,
           'user_img'           => $image
        ]);

        $userData["user_img"] = ImgHelper::returnImageLink($userData["user_img"]);

        return $userData;
    }

    public static function updateUserProfile($data, $image = null)
    {
        if (is_null($image)){
            return self::where('user_id', '=', $data->user_id)
                ->update(array(
                    'user_name'    => $data->user_name,
                    'user_email'   => $data->user_email,
                    'user_address' => $data->user_address,
                ));
        }
        else {

            return self::where('user_id', '=', $data->user_id)
                ->update(array(
                    'user_name'    => $data->user_name,
                    'user_email'   => $data->user_email,
                    'user_address' => $data->user_address,
                    'user_img'     => $image,
                ));
        }
    }

    public static function updateVendorProfile($data, $image = null)
    {
        if (is_null($image)){
            return self::where('user_id', '=', $data->user_id)
                ->update(array(
                    'user_name'    => $data->user_name,
                    'user_email'   => $data->user_email,
                ));
        }
        else {

            return self::where('user_id', '=', $data->user_id)
                ->update(array(
                    'user_name'    => $data->user_name,
                    'user_email'   => $data->user_email,
                    'user_img'     => $image,
                ));
        }

    }

    public static function countNewUsers($days)
    {
        $currentTime =  Carbon::now();
        $time = $currentTime->subDays($days);
        return  count(self::where('user_type', '=', 'user')->where('created_at', '>', $time)->get());
    }

    public static function getUsersByType($type)
    {
        // $type => user || vendor

        $users =
            self::query()
                ->select
                (
                    'users.user_id',
                    'users.user_name',
                    'users.user_phone',
                    'users.user_email',
                    'users.user_address',
                    'users.user_is_active'
                )
                ->where('user_type', $type);

        if ($type == 'vendor'){

            $users = $users->addSelect('vendor_details.vendor_type')->join('vendor_details', 'vendor_details.user_id', 'users.user_id');
        }

        return $users->paginate(15);
    }

    public static function updateActivationStatus($userId, $status)
    {
        //$status => 0 || 1
        self::where('user_id', '=', $userId)
            ->update(array(
                'user_is_active' => $status,
                'updated_at'     => now()
            ));

    }


    public static function getUsersHaveOrdersWithFilters($userType, $dateFrom, $dateTo, $orderStatus = null)
    {
        $users =
            self::query()
                ->select
                (
                    'users.user_id',
                    'users.user_name',
                    'users.user_phone',
                    'users.user_email',
                    'users.user_address',
                    'users.user_is_active',
                    DB::raw('count(order_id) as orders_count')
                )

                ->where('user_type', $userType);

        if ($userType == 'vendor'){
            $users = $users
                    ->addSelect('vendor_details.vendor_type')
                    ->join('vendor_details', 'vendor_details.user_id', 'users.user_id')
                    ->join('orders','orders.vendor_id','=','users.user_id');
        }
        else{
            $users = $users
                    ->join('orders','orders.user_id','=','users.user_id');
        }

        if ($orderStatus != null){
            $users = $users->where('orders.order_status','=', $orderStatus);
        }

        $users = $users->where('orders.created_at','>=', $dateFrom)
                       ->where('orders.created_at','<=', $dateTo)
                       ->groupBy('user_id');

        return $users->get();
    }

    public static function getUserTypeVendor($userId)
    {
        $vendor =
            self::query()
                ->select
                (

                    'user_name as vendor_name',
                    'user_phone as vendor_phone',
                    'user_email as vendor_email',
                    'user_address as vendor_address',
                    'user_lat as vendor_lat',
                    'user_long as vendor_long',
                    'user_img as vendor_img'
                )
                ->where('user_id', $userId)
                ->first();

        if (!is_null($vendor)){
            $vendor->vendor_img =  ImgHelper::returnImageLink($vendor->vendor_img);
        }

        return $vendor;
    }

    public static function getUserWithTypeAndById($userId, $type)
    {
        $user =  self::where('user_id', $userId)->where('user_type', $type)->first();
        if (!is_null($user)){
            $user->user_img = ImgHelper::returnImageLink($user->user_img);
        }

        return $user;

    }


    public static function saveAdminData($data, $userId = null, $options = null)
    {
        // $options (array) => image, password

        $dataToBeSaved['user_type']          = 'admin';
        $dataToBeSaved['user_name']          = $data['user_name'];
        $dataToBeSaved['user_address']       = $data['user_address'];
        $dataToBeSaved['user_email']         = $data['user_email'];
        $dataToBeSaved['user_phone']         = $data['user_phone'];
        $dataToBeSaved['user_date_of_birth'] = $data['user_date_of_birth'];
        $dataToBeSaved['user_is_active']     = $data['user_is_active'];

        if (isset($options['password'])){
            $dataToBeSaved['password'] = $options['password'];
        }

        if (isset($options['user_img'])){
            $dataToBeSaved['user_img'] = $options['user_img'];
        }

        if (is_null($userId)){
            //create
            $dataToBeSaved['created_at'] = now();
            $dataToBeSaved['updated_at'] = now();
            return self::create($dataToBeSaved);
        }
        else{
            //update
            return self::where('user_id', '=', $userId)
                ->update($dataToBeSaved);
        }


    }

}
