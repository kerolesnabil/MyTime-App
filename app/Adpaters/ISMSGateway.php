<?php

namespace App\Adpaters;


interface ISMSGateway
{

    public function sendSms($phone, $message, $language);

}
