<?php

namespace App\jobs;

use App\Models\NotificationToken;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class sendPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data     = [];
    public $userIds  = [];
    public $tokens   = [];
    public $sound    = "1";
    public $app      = "user";

    private $credentials = [
        "user"    => "AAAAUZeB7NA:APA91bFq5j8VUCdV8VbVraVACLgrdKG8UN0RAvUh1WLEmkxwxKnpFQT6GrnO3xDbreFQdXNKLvdwQNjSiS5cNPVOzBkSosddx9hae7hmWe81VBvBqYFbOl6iH5MK2jGerUDfmCxC3be8",
        "vendor"  => "",
        "browser" => "",
    ];

    private function prepareNotficationData($params){

        return [
            "title"        => $params["title"],
            "body"         => $params["body"] ?? $params["title"],
            "extraPayload" => [
                "type" => $params['extraPayload']['type'] ?? "general",
                "url"  => $params['extraPayload']['id'] ?? "",
            ],
        ];

    }

    private function getUserTokens($params){

        if(isset($params["tokens"])){
            return $params["tokens"];
        }

        return collect(NotificationToken::getTokensByUsersIds($params["userIds"]))->pluck('token')->toArray();
    }

    public function __construct($params)
    {

        /*
        $params = [
            'data'     => [
                "title"        => "title",
                "body"         => "body",
                "extraPayload" => [
                    "type" => "general",
                    "id"  => "link",
                ],
            ],
            'tokens'   => [],
            'userIds'  => [],
            'toApp'    => "",
            'allowLog' => true,
        ];
        */

        $data  = $this->prepareNotficationData($params);
        $toApp = $params["toApp"] ?? "browser";


        if(!in_array($toApp, ["browser"]))
        {
            $toApp = "browser";
        }

        $this->app          = $toApp;
        $this->data         = $data;
        $this->tokens       = $this->getUserTokens($params);
        $this->allowLog     = $params["allowLog"] ?? false;
    }


    public function handle()
    {

        if(is_array($this->tokens) && count($this->tokens) && in_array($this->app, ["browser", "user", "vendor"]))
        {

            $devicesCollection = collect($this->tokens);
            $devices           = $devicesCollection->pluck('token')->all();

            $this->sendToDevices($devices);

//            if($this->allowLog)
//            {
//                $this->logUserPush($userIds);
//            }

        }

    }

    private function sendToDevices($devices)
    {

        if (is_array($devices) && count($devices) > 0)
        {

            $devices    = array_unique($devices);

            $header = [
                'Authorization: Key=' . $this->credentials[$this->app],
                'Content-Type: Application/json'
            ];

            $msg = [
                'icon'         => $this->data['icon'] ?? "",
                'image'        => $this->data['image'] ?? "",
                'title'        => $this->data['title'] ?? "",
                'body'         => $this->data['body'] ?? "",
                'sound'        => $this->sound,
                'extraPayload' => $this->data['extraPayload'] ?? [],
            ];

            $payload = [
                'registration_ids' 	=> $devices,
                'data'				=> $msg
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode( $payload ),
                CURLOPT_HTTPHEADER => $header
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);

            curl_close($curl);

        }
    }


}
