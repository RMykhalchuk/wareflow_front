<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait CheckEmailTrait
{
    public function existsEmail($request): bool
    {
        $login = $request['login'];

        $validator = Validator::make(['email' => $login], [
            'email' => 'required|email'
        ]);

        return $validator->passes();
    }
}
