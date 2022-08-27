<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationToken extends Model
{
    use HasFactory;

    protected $primaryKey='not_token_id';

    protected $fillable=[
        'user_id','token','device_type','app_version_id'
    ];

    public static function createNotificationToken($data)
    {
        self::create([
            'user_id'=>$data->user_id,
            'token'=>$data->token,
            'device_type'=>$data->header('Device-Type'),
            'app_version_id'=>$data->header('App-Version-Id')
        ]);
    }



}
