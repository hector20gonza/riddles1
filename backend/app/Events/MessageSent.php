<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $text;
    public $roomId;
    public function __construct($user,$text,$roomId)
    {
     $this->text=$text;
     $this->user=$user;
     $this->roomId=$roomId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
   public function broadcastOn()
    {
        return new Channel('chat.'.$this->roomId);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
   
}
