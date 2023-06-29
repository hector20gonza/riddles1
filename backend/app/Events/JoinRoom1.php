<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class JoinRoom1 implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   
    private $roomId;
    private  $user;
    public function __construct($user,$roomId)
    {
 
        $this->roomId = $roomId;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn():array
    {
        

        return [
            new   PresenceChannel('online_users.'.$this->roomId),
        ];
    }
   
   
   

    
    
}
