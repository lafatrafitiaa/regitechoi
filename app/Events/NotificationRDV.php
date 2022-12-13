<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationRDV implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $information;

    public $token;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($information, $token)
    {
        $this->information = $information;
        $this->token = $token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [$this->token];
    }

    public function broadcastAs(){
        return 'new-rendezvous';
    }

    public function braodcastWith () {
        return ["data" => $this->information];
    }
}
