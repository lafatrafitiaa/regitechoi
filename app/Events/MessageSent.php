<?php

namespace App\Events;

use App\Models\Messages;
use App\Models\Utilisateur;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Expr\Cast\String_;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $idUtilisateur;
    /**
     * Message details
     *
     * @var string
     */
    public $message;

    /**
     *
     */
    public $token;
    // /**
    //  * @var Date
    //  */
    // public $dateHeureChat;

    public $mytoken;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $message, string $idUtilisateur, string $token, string $mytoken)
    {
        $this->idUtilisateur = $idUtilisateur;
        $this->message = $message;
        $this->token = $token;
        $this->mytoken = $mytoken;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [$this->token];
        // return ['my-channel'];
    }

    public function broadcastAs()
    {
        return 'new-message';
    }

    public function braodcastWith () {
        return ["data" => $this->message];
    }

}
