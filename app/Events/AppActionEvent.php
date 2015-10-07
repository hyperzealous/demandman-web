<?php

namespace App\Events;

use App\Events\Event;
use App\ActionRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Appliance;

class AppActionEvent extends Event implements ShouldBroadcastNow
{
    use SerializesModels;

    public $actionRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ActionRequest $actReq)
    {
	$this->actionRequest = $actReq;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['dm.' . $this->actionRequest->requestId() . ".". $this->actionRequest->getAction()];
    }
}
