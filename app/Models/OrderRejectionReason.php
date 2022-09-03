<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use App\Helpers\ImgHelper;



class OrderRejectionReason extends Model
{
    use HasFactory;
    use AbstractionModelTrait;


    protected $table = "order_rejections_reasons";
    protected $primaryKey = "rejection_reason_id";
    protected $guarded = ['rejection_reason_id'];
    protected $fillable =
    [
        'rejection_reason',
    ];



    public static function getAllReasons()
    {
        return self::query()
            ->select
            (
                'rejection_reason_id',
                self::getValueWithSpecificLang('rejection_reason', app()->getLocale(),'rejection_reason')
            )->get();
    }

    public static function getReasonById($reasonId)
    {
        $reason =
            self::query()
                ->select(
                    'rejection_reason_id',
                    'rejection_reason'
                )
                ->where('rejection_reason_id', '=', $reasonId)
                ->first();
        return $reason;
    }

    public static function updateReasonData($data)
    {

        return self::where('rejection_reason_id', '=', $data['rejection_reason_id'])
            ->update(array(
                'rejection_reason' => $data['rejection_reason']
            ));
    }


    public static function createReason($data)
    {
        return self::create([

            'rejection_reason' => $data['rejection_reason'],
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

    }

}
