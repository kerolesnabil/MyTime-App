<?php


namespace App\Adpaters\Implementation;


use App\Adpaters\ISMSGateway;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class HISMS implements ISMSGateway
{
    public $client;

    public function __construct()
    {
        if (!$this->client) {
            $this->client = new Client();
        }

    }

    /**
     * SET YOUR CLIENT TO MOVE FORWARD TO SEND A NEW SMS.
     *
     * @param Client $client
     *
     * @return $this
     */
    private function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    public function sendSms($phone, $message, $language = 'en')
    {
        $params = [
            'send_sms' =>"",
            'username' =>"966583798899",
            'password' =>"MYtime090@5BTax7",
            'numbers' => '966'.$phone,
            'sender'=>'MY TIME',
            'message' =>$message,
            'date' => date('Y-m-d'),
            'time' => date("H:i"),
        ];

        try {
            $smsURL = "https://www.hisms.ws/api.php?".http_build_query($params);

            $response = $this->client->get($smsURL);

            return true;

        }
        catch (\Exception $e) {
            info("HISMS has been trying to send sms to $phone but operation failed !");
            return false;
        }

    }


}
