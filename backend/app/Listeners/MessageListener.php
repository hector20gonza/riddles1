<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Events\messagerecived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle( messagerecived $event): void
    {
        $messagerecived = $event->text;

        event(new MessageSent('message', $messagerecived));
    }
}
