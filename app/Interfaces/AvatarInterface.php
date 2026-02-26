<?php

namespace App\Interfaces;

use App\Models\User;

/**
 * AvatarInterface.
 */
interface AvatarInterface
{
    /**
     * @param $request
     * @param User $user
     * @return mixed
     */
    public function setAvatar($request, User $user);

    /**
     * @param User $user
     * @return mixed
     */
    public function deleteAvatarIfExist(User $user);
}
