<?php

namespace App\Http\Requests\Web\Workspace;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class StoreWorkspaceRequest extends FormRequest
{
    use RequestJson;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'image' => [
                'required',
                File::types(['png', 'jpeg', 'jpg','gif'])
                    ->max(800)
            ],
        ];
    }
}
