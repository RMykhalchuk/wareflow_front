<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\RegisterRequest;
use App\Jobs\SendVerificationCode;
use App\Services\Web\Auth\Register\AuthRegisterServiceInterface;
use Illuminate\Http\JsonResponse;
use App\Traits\VerificationCodeTrait;

final class RegisterController extends Controller
{
    use VerificationCodeTrait;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    public function __construct(private AuthRegisterServiceInterface $registerService) {}

    public function create(RegisterRequest $request)
    {
        return $this->registerService->create($request);
    }

    public function register(RegisterRequest $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        return $this->registerService->register($request);
    }

    public function sendVerificationCode(RegisterRequest $request): \Illuminate\Http\Response
    {
        $this->sendCode($request->validated());

        return response('Code successfully sent');
    }
}
