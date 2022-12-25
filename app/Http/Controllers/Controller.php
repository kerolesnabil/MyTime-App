<?php

namespace App\Http\Controllers;

use App\Jobs\sendPush;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

        /*dispatch(new sendPush([
            'data'     => [
                "title"        => "order stauts",
                "body"         => "your order is done",
                "extraPayload" => [
                    "type" => "order",
                    "id"  => "2",
                ],
            ],
            'userIds'  => [1],
            'toApp'    => "user",
            'allowLog' => true,
        ]));*/

        /*dispatch(new sendPush([
            'data'     => [
                "title"        => "new order is reuqiest",
                "body"         => "your order is done",
                "extraPayload" => [
                    "type" => "order",
                    "id"  => "20",
                ],
            ],
            'userIds'  => [10],
            'toApp'    => "vendor",
            'allowLog' => true,
        ]));*/


    }



}
