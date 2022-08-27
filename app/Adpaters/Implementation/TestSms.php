<?php


namespace App\Adpaters\Implementation;


use App\Adpaters\ISMSGateway;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class TestSms implements ISMSGateway
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
        return true;

    }


}
