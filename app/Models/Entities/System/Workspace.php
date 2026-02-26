<?php

namespace App\Models\Entities\System;

use App\Interfaces\StoreFileInterface;
use App\Models\Entities\Company\Company;
use App\Models\User;
use App\Services\Web\Auth\GuardContext;
use App\Traits\WorkspaceDataTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

final class Workspace extends Model
{
    use SoftDeletes;
    use WorkspaceDataTrait;
    use HasFactory;

    protected $guarded = [];

    public function owner(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function usersInWorkspace(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_working_data');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<Company>
     */
    public function companies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Company::class, 'workspace_id', 'id');
    }

    public static function store(\Illuminate\Http\Request $request)
    {
        $data = $request->except(['_token', 'avatar']);
        $data['user_id'] = Auth::id();

        $workspace = Workspace::create($data);

        $workspace->setAvatar($request);

        return $workspace->id;
    }

    public function updateData(\App\Http\Requests\Web\Workspace\UpdateWorkspaceRequest $request)
    {
        $data = $request->except(['_token', 'avatar']);

        if ($request->hasFile('avatar') || $request->get('avatar_color')) {
            $this->setAvatar($request);
        }

        $this->fill($data);
        $this->save();

        return $this->id;
    }

    public function setAvatar(\Illuminate\Http\Request $request): void
    {
        if ($request->hasFile('avatar')) {
            $file = resolve(StoreFileInterface::class);
            $file->setFile($request->file('avatar'), 'workspace/avatars', $this, 'avatar_type');
        }

        if ($request->avatar_color) {
            $this->avatar_color = $request->avatar_color;
        }

        $this->save();
    }

    public static function current()
    {
        $guard = app(GuardContext::class)->getGuard();

        return auth($guard)->user()->current_workspace_id;
    }
}
