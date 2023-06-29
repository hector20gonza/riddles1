<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

     public $resp;
     public $user;
     public $roomId;
    public function __construct($user,$respuesta,$roomId)
    {
      $this->resp=$respuesta;
      $this->user=$user;
      $this->roomId=$roomId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('AnswerSub.'.$this->roomId),
        ];
    }
    public function broadcastAs()
    {
        return 'response.sent';
    }
}
