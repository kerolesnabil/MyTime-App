<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";
    protected $primaryKey='not_id';

    protected $fillable=[
        'from_user_id', 'to_user_id', 'not_title',
        'not_type', 'action', 'is_seen'
    ];

    public static function showNotificationsByUserId($userId)
    {
        $notifications =self::query()
            ->select
            (
                'not_id',
                'not_title',
                'not_type',
                'action',
                'is_seen'
            )
            ->where('to_user_id','=', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $notifications;

    }

    public static function updateNotificationsIsSeenColByUserId($userId)
    {

    }


}
