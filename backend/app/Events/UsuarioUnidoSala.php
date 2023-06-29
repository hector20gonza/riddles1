<?
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UsuarioUnidoSala implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $usuario;

    /**
     * Create a new event instance.
     *
     * @param  User  $usuario
     * @return void
     */
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('sala');
    }
}