<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;
    use AbstractionModelTrait;


    protected $table = "notifications";
    protected $primaryKey='not_id';

    protected $fillable=[
        'to_user_id', 'not_title', 'not_type', 'action', 'is_seen'
    ];

    public static function showNotificationsByUserId($userId)
    {
        $notifications =self::query()
            ->select
            (
                'not_id',
                self::getValueWithSpecificLang('not_title', app()->getLocale(),'not_title'),
                'not_type',
                'action',
                'is_seen',
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as not_created_at')

            )
            ->where('to_user_id','=', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $notifications;

    }

    public static function updateNotificationsIsSeenColByUserId($userId)
    {
        self::where('to_user_id', '=', $userId)
            ->update(array(
                'is_seen'    => 1
            ));

    }


}
