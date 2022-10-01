<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateAd
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $walletValue;
    public $adCost;


    public function __construct(
        $userId,
        $walletValue,
        $adCost

    )
    {
        $this->userId      = $userId;
        $this->walletValue = $walletValue;
        $this->adCost      = $adCost;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
