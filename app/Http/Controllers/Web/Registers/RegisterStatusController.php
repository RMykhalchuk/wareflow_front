<?php

namespace App\Http\Controllers\Web\Registers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Registers\RegisterRequest;
use App\Models\Dictionaries\Register;
use Carbon\Carbon;

final class RegisterStatusController extends Controller
{
    //TODO Need refactor with localization logic
    public function registerStatus(RegisterRequest $request, Register $register): void
    {
        $data = $request->validated();
        $data['status'] = 'Зареєстровано';
        $data['register'] = Carbon::now()->toDateTimeString();
        $register->updateWithRelations($data);
    }

    public function applyStatus(RegisterRequest $request, Register $register): void
    {
        $data = $request->validated();
        $data['status'] = 'Підтверджено';
        $register->updateWithRelations($data);
    }

    public function launchStatus(RegisterRequest $request, Register $register): void
    {
        $data = $request->validated();
        $data['status'] = 'Запущено';
        $data['entrance'] = Carbon::now()->toDateTimeString();
        $register->updateWithRelations($data);
    }

    public function cancelEntrance(RegisterRequest $request, Register $register): void
    {
        $data = $request->validated();
        $data['status'] = 'Зареєстровано';
        $register->updateWithRelations($data);
    }

    public function releasedStatus(RegisterRequest $request, Register $register): void
    {
        $data = $request->validated();
        $data['status'] = 'Поза територією';
        $data['departure'] = Carbon::now()->toDateTimeString();
        $register->updateWithRelations($data);
    }
}
