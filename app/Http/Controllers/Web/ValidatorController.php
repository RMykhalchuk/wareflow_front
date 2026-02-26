<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\API\ApiPasswordRequest;
use App\Http\Requests\Web\User\API\ApiPinRequest;
use App\Models\User;
use Illuminate\Http\Request;

final class ValidatorController extends Controller
{


    public function validateUserInWorkspace(Request $request): bool
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return (bool)$user->workingData;
        }

        return false;
    }

    public function validatePasswordData(ApiPasswordRequest $request): \Illuminate\Http\Response
    {
        return response('OK');
    }

    public function validatePinData(ApiPinRequest $request): \Illuminate\Http\Response
    {
        return response('OK');
    }
}
