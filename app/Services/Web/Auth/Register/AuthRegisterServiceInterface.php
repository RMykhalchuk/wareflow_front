<?php

namespace App\Services\Web\Auth\Register;

use App\Http\Requests\Web\Auth\RegisterRequest;
use App\Models\User;


interface AuthRegisterServiceInterface {
    public function create(RegisterRequest $request) : User;
    public function register(RegisterRequest $request);
}
