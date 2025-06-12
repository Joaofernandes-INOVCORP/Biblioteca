<?php

namespace App\Events;

use App\Models\ChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $message, public ChatRoom $chat)
    {
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat_' . $this->chat->name);
    }

    public function broadcastAs()
    {
        return 'messageReceive';
    }
}