<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entities\User\VerificationCodes;
use Illuminate\Http\Request;

final class VerificationCodeController extends Controller
{
    public function validateCode(Request $request): \Illuminate\Http\JsonResponse
    {
        $login = $request->get('login');
        $code = $request->get('code');

        $exists = VerificationCodes::where('login', $login)->where('code', $code)->first();

        if ($exists) {
            return response()->json([
                'message' => 'Successful validation.'
            ]);
        } else {
            return response()->json([
                'message' => 'Failed validation.'
            ], 422);
        }
    }
}
