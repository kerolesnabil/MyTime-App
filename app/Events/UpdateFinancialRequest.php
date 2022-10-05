<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateFinancialRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requestId;
    public $requestType;
    public $status;

    public function __construct(
        $requestId,
        $requestType,
        $status

    )
    {
        $this->requestId     = $requestId;
        $this->requestType   = $requestType;
        $this->status        = $status;

    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
