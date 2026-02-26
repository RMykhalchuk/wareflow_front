<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\ResetPasswordRequest;
use App\Models\Entities\User\VerificationCodes;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */


    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function reset(ResetPasswordRequest $request)
    {
        $login = $request->get('login');
        $code = $request->get('code');

        $codeObj = VerificationCodes::where('login', $login)->where('code', $code)->first();

        if (!$codeObj) {
            return response()->json([
                'message' => 'Failed validation.'
            ], 422);
        } else {
            $codeObj->delete();
        }

        $user = User::where(function ($q) use ($login) {
            $q->where('email', $login)->orWhere('phone', $login);
        })->first();

        $user->password = Hash::make($request->get('password'));
        $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));

        Auth::guard()->login($user);
    }
}
