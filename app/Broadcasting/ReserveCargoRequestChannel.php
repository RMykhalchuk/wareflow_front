<?php

namespace App\Broadcasting;

use App\Models\User;

final class ReserveCargoRequestChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param \App\Models\User  $user
     */
    public function join(User $user): void
    {
        //
    }
}
