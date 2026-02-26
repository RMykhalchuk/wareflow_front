<?php

namespace App\Services\Web\File;

use App\Interfaces\StoreImageInterface;
use App\Models\Entities\System\FileLoad;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

final class StoreImage implements StoreImageInterface
{
    /**
     * @return void
     */
    #[\Override]
    /**
     * @param \Illuminate\Database\Eloquent\Model $model

     * @return void
     */
    public function setImage($request, Model $model, string $path, string $column = 'img_type')
    {
        if ($model) {
            $this->deleteImage($model, $path, $column);
            FileLoad::where('path', $path)->where('new_name', 'like', $model->id . '.%')
                ->delete();
        }
        $extension = $request->file('image')->extension();

        $request->file('image')->move(storage_path('uploads/' . $path), $model->id . '.' . $extension);

        FileLoad::create([
            'name' => $request->file('image')->getClientOriginalName(),
            'path' => $path,
            'new_name' => $model->id . '.' . $extension,
            'user_id' => Auth::id(),
            'creator_company_id' => app(AuthContextService::class)->getCompanyId()
        ]);

        $model[$column] = $extension;
        $model->save();
    }


    /**
     * @return void
     */
    #[\Override]
    /**
     * @return void
     */
    public function deleteImage(Model $model, string $path, string $column = 'img_type')
    {
        if ($model[$column]) {
            $newPath = storage_path('uploads/' . $path . '/' . $model->id . '.' . $model[$column]);
            if (File::exists($newPath)) {
                File::delete($newPath);
                FileLoad::where('path', $path)->where('user_id', Auth::id())
                    ->delete();
                $model[$column] = null;
                $model->save();
            }
        }
    }
}
