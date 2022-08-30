<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
}
