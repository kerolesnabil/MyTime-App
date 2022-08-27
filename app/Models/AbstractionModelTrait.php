<?php
/**
 * Created by PhpStorm.
 * User: boiar
 * Date: 24/07/22
 * Time: 11:07 م
 */

namespace App\Models;
use Illuminate\Support\Facades\DB;

trait AbstractionModelTrait
{
    public static function getValueWithSpecificLang($col_name, $lang, $alias_name)
    {
        /**
         * @param string $col_name     => column name in database
         * @param string $lang         => language
         * @param string $alias_name   => alias for column name
         */
        return DB::raw("json_unquote(json_extract($col_name, '$.$lang')) as $alias_name");

    }
}