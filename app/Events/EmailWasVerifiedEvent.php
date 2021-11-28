<?php

namespace App\Events;

use App\Models\User\User;
use Illuminate\Queue\SerializesModels;

class EmailWasVerifiedEvent
{
    use SerializesModels;

    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
