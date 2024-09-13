<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SocketMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $messenger)
    {
        //
    }

    public function broadcastWith(): array 
    {
        return [
            'message' => new MessageResource($this->messenger)
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $mess = $this->messenger;
        $channels = [];

        if ($mess->group_id) {
            $channels[] = new PrivateChannel('message.group.' . $mess->group_id);
        }else{
            $channels[] = new PrivateChannel('message.user.' . collect([$mess->sender_id, $mess->receiver_id])->sort()->implode('-'));
        }

        return $channels;
    }
}
