<?php

namespace App\Services\Web\User;

use App\Interfaces\AvatarInterface;
use App\Models\Entities\System\FileLoad;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

final class Avatar implements AvatarInterface
{
    /**
     * @return void
     */
    #[\Override]
    /**
     * @return void
     */
    public function setAvatar($request, \App\Models\User $user)
    {
        $this->deleteAvatarIfExist($user);

        $extension = $request->file('avatar')->extension();
        $request->file('avatar')->move(storage_path('uploads/user/avatars'), $user->id . '.' . $extension);

        FileLoad::create([
            'name' => $request->file('avatar')->getClientOriginalName(),
            'path' => 'user/avatars',
            'new_name' => $user->id . '.' . $extension,
            'user_id' => Auth::id(),
            'creator_company_id' => User::currentCompany()
        ]);

        $user->avatar_type = $extension;
        $user->save();
    }


    /**
     * @return void
     */
    #[\Override]
    /**
     * @return void
     */
    public function deleteAvatarIfExist(\App\Models\User $user)
    {
        if ($user->avatar_type) {
            $path = storage_path('uploads/user/avatars/' . $user->id . '.' . $user->avatar_type);
            if (File::exists($path)) {
                File::delete($path);
                FileLoad::where('path', 'user/avatars')
                    ->where('new_name', $user->id . '.' . $user->avatar_type)
                    ->delete();
                $user->avatar_type = null;
                $user->save();
            }
        }
    }
}
