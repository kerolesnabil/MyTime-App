<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DecreaseWallet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $amountWillDecrease;
    public $notes;


    public function __construct(
        $userId,
        $amountWillDecrease,
        $notes
    )
    {
        $this->userId             = $userId;
        $this->amountWillDecrease = $amountWillDecrease;
        $this->notes              = $notes;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
