<?php

namespace App\Events;

use App\Models\Rating;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ratings'),
        ];
    }
}
