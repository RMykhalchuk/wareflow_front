<?php

namespace App\Services\Web\Auth\Register;

use App\Http\Requests\Web\Auth\RegisterRequest;
use App\Models\Entities\User\VerificationCodes;
use App\Models\User;
use App\Traits\CheckEmailTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRegisterService implements AuthRegisterServiceInterface
{
    use CheckEmailTrait;

    public function create(RegisterRequest $request): User
    {
        $login = $request->get('login');

        $data['password'] = Hash::make($request->get('password'));

        if ($this->existsEmail($request)) {
            $data['email'] = $login;
        } else {
            $data['phone'] = $login;
        }

        return User::create($data);
    }


    public function register(RegisterRequest $request)
    {
        $code = $request->get('code');

        $codeObj = VerificationCodes::where('login', $request->get('login'))
            ->where('code', $code)
            ->first();
        if (!$codeObj) {
            return response()->json(['message' => 'Wrong code!'], 422);
        }

        $user = $this->create($request);

        event(new Registered($user));

        VerificationCodes::where('login', $request->get('login'))->delete();

        Auth::guard('web')->login($user);

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect()->route('onboarding');
    }
}
