<?php

namespace App\Services\Web\User;

use App\Http\Requests\Web\User\PasswordRequest;
use App\Http\Requests\Web\User\UserRequest;
use App\Models\Dictionaries\ExceptionType;
use App\Models\Dictionaries\Position;
use App\Models\Entities\System\FileLoad;
use App\Models\Entities\System\Workspace;
use App\Models\Entities\User\UserWorkingData;
use App\Models\User;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

/**
 * UserService.
 */
final class UserService
{
    /**
     * @return array
     */
    public static function create(): array
    {
        $user = null;

        if (array_key_exists('email', $_GET)) {
            $user = User::where('email', $_GET['email'])->first();
        } elseif (array_key_exists('phone', $_GET)) {
            $user = User::where('phone', $_GET['phone'])->first();
        }

        $positions = Position::all();
        $exceptions = ExceptionType::all('id', 'name', 'key');

        $roles = Role::where('visible', 1)->orWhere('creator_company_id', User::currentCompany())
            ->get(['id', 'name', 'title']);


        return [
            'positions' => $positions,
            'exceptions' => $exceptions,
            'roles' => $roles,
            'user' => $user
        ];
    }

    /**
     * @param UserRequest $request
     * @return User
     */
    public static function store(UserRequest $request): User
    {
        $userData = $request->validated();
        $companyId = app(AuthContextService::class)->getCompanyId();
        $positionId = (Position::where('key', $userData['position'])->first('id'))->id;
        $user = User::where('email', $userData['email'])->orWhere('phone', $userData['phone'])->first();

        if (!$user) {
            $payload = array(
                'name' => $userData['name'],
                'surname' => $userData['surname'],
                'patronymic' => $userData['patronymic'],
                'birthday' => $userData['birthday'],
                'phone' => $userData['phone'],
                'email' => $userData['email'],
                'sex' => $userData['sex'],
                'new_user' => false,
                'password' => Hash::make($userData['password'])
            );

            if ($request->has('pin')) {
                $payload['pin'] = (string)$userData['pin'];
            }

            $user = User::create($payload);
        }

        $workspaceId = Workspace::current();

        if (!UserWorkingData::where('user_id', $user->id)->where('workspace_id', $workspaceId)->exists()) {
            $workingData = UserWorkingData::create(
                [
                    'position_id' => $positionId,
                    'company_id' => $companyId,
                    'user_id' => $user->id,
                    'workspace_id' => $workspaceId,
                    'creator_company_id' => User::currentCompany(),
                ]);

            $workingData->assignRole($userData['role']);

            if ($workingData->position->key == 'driver') {
                $workingData->saveDriver($request);
            }
        } else {
            throw ValidationException::withMessages(
                [
                    'email' => $user->email === $userData['email']
                        ? ['User with this email exists.']
                        : [],
                    'phone' => $user->phone === $userData['phone']
                        ? ['User with this phone exists.']
                        : [],
                ]);
        }

        $user->setAvatar($request);

        if ($request->has('warehouse_ids')) {
            $ids = array_values(array_filter(
                                    (array)$request->input('warehouse_ids'),
                                    fn($v) => $v !== null && $v !== ''
                                ));

            if (!empty($ids)) {
                $workingData->warehouses()->sync($ids);
            }
        }

        return $user;
    }

    /**
     * @param User $user
     * @return array
     */
    public static function update(User $user): array
    {
        $dataArray['positions'] = Position::all();
        $dataArray['exceptions'] = ExceptionType::all('id', 'name', 'key');


        if ($user->workingData?->position?->key == 'driver') {
            $dataArray['healthBookFile'] = FileLoad::where('path', 'driver/health_book')
                ->where('new_name', $user->workingData->id . '.' . $user->workingData->health_book_doctype)
                ->first();

            $dataArray['drivingLicenseFile'] = FileLoad::where('path', 'driver/driving_license')
                ->where('new_name', $user->workingData->id . '.' . $user->workingData->driving_license_doctype)
                ->first();
        }

        $dataArray['user'] = $user;
        $dataArray['roles'] = Role::where('visible', 1)->orWhere('creator_company_id', User::currentCompany())
            ->get(['id', 'name', 'title']);


        return $dataArray;
    }

    /**
     * @param PasswordRequest $request
     * @param User $user
     * @return JsonResponse|void
     */
    public static function changePassword(PasswordRequest $request, User $user): ?JsonResponse
    {
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['errors' => ['old_password' => 'Не правильно введний старий пароль. Спробуйте ще раз']]);
        } elseif ($request->login && $user->login != $request->login) {
            return response()->json(['errors' => ['login' => 'Не правильно введний логін']]);
        }

        $user->update([
                          'password' => Hash::make($request->new_password)
                      ]);
    }
}
