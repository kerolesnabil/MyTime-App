<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;


class SupportMessage extends Model
{
    use HasFactory;

    protected $table = "support_messages";
    protected $primaryKey = "support_message_id";
    protected $guarded = ['support_message_id'];
    protected $fillable = ['user_id', 'user_name', 'phone', 'message'];

    public static function createSupportMessage($userId, $data)
    {

        return self::create([
            "user_id"   => $userId,
            "user_name" => $data['name'],
            "phone"     => $data['phone'],
            "message"   => $data['message'],
        ]);
    }


}
