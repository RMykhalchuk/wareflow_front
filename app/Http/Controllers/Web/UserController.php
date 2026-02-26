<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\AvatarRequest;
use App\Http\Requests\Web\User\ChangePasswordRequest;
use App\Http\Requests\Web\User\PasswordRequest;
use App\Http\Requests\Web\User\UpdateCurrentWarehouseRequest;
use App\Http\Requests\Web\User\UpdateOnboardingRequest;
use App\Http\Requests\Web\User\UserRequest;
use App\Interfaces\AvatarInterface;
use App\Mail\SendPasswordEmail;
use App\Models\Dictionaries\ExceptionType;
use App\Models\Dictionaries\Position;
use App\Models\Entities\System\FileLoad;
use App\Models\Entities\User\UserWorkingData;
use App\Models\Entities\User\VerificationCodes;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use App\Services\Web\Auth\Register\AuthRegisterServiceInterface;
use App\Services\Web\User\UserService;
use App\Tables\User\TableFacade;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

final class UserController extends Controller
{
    //TODO Need to refactor
    public function updateData(UserRequest $request, User $user): JsonResponse
    {
        $user->updateData($request);

        return response()->json(['success' => 'Data updated']);
    }

    public function changePassword(PasswordRequest $request, User $user): JsonResponse
    {
        UserService::changePassword($request, $user);

        return response()->json(['success' => '']);
    }


    public function updateAvatar(AvatarRequest $request, User $user, AvatarInterface $avatar): RedirectResponse
    {
        $avatar->setAvatar($request, $user);

        return redirect()->back();
    }

    public function deleteAvatar(User $user, AvatarInterface $avatar): RedirectResponse
    {
        $avatar->deleteAvatarIfExist($user);

        return redirect()->back();
    }

    public function update(User $user): View
    {
        $dataArray = UserService::update($user);

        $dataArray['warehouses'] = Warehouse::query()
            ->orderBy('name')
            ->get(['id','name', 'creator_company_id']);
        $dataArray['user'] = ($dataArray['user'] ?? $user)
            ->loadMissing('workingData.warehouses');

        return view('user.update-profile', $dataArray);
    }

    public function users(): View
    {
        $usersAll = User::all();
        $positions = Position::all();

        return view('user.all', compact('positions', 'usersAll'));
    }

    public function create(): View
    {
        $data = UserService::create();

        $data['warehouses'] = Warehouse::query()
            ->orderBy('name')
            ->get(['id', 'name', 'creator_company_id']);

        $data['user'] = $data['user'] ?? null;

        return view('user.create', $data);
    }

    public function store(UserRequest $request): Response
    {
        UserService::store($request);

        return response('OK');
    }

    public function show(User $user): View
    {
        if ($user->workingData?->position?->key == 'driver') {
            $dataArray['healthBookFile'] = FileLoad::where('path', 'driver/health_book')
                ->where('new_name', $user->workingData->id . '.' . $user->workingData->health_book_doctype)
                ->first();

            $dataArray['drivingLicenseFile'] = FileLoad::where('path', 'driver/driving_license')
                ->where('new_name', $user->workingData->id . '.' . $user->workingData->driving_license_doctype)
                ->first();
        }

        $role = $user?->workingData?->role;

        $dataArray['user'] = $user;
        $dataArray['exceptions'] = ExceptionType::all('id', 'name');

        $dataArray['role'] = isset($role[0]) ? $role[0]->title : null;

        return view('user.user-page', $dataArray);
    }

    public function showChangeTempPasswordForm(): View
    {
        return view('auth.passwords.change-temp-password');
    }

    public function changeTempPassword(ChangePasswordRequest $request): RedirectResponse
    {
        $user = \auth()->user();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        Auth::guard()->login($user);

        return redirect()->route('user-board');
    }

    public function destroy($user): RedirectResponse
    {
        UserWorkingData::where('user_id', $user->id)->delete();

        return redirect()->route('user-board');
    }

    public function filter()
    {
        return TableFacade::getFilteredData();
    }

    public function sendPassword(Request $request): Response
    {
        Mail::to($request->email)->send(new SendPasswordEmail($request->password));

        return response('OK');
    }

    public function updateOnboarding(UpdateOnboardingRequest $request): Response
    {
        Auth::user()->updateOnboarding($request->validated());

        return response('OK');
    }

    /**
     * @param UpdateCurrentWarehouseRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function updateCurrentWarehouse(UpdateCurrentWarehouseRequest $request, User $user): JsonResponse
    {
        $result = $user->setCurrentWarehouseApp($request->validated('current_warehouse_id'));

        if (!$result['success']) {
            return response()->json(['ok' => false, 'error' => 'No working data for current workspace'], 422);
        }

        return response()->json([
            'ok'                   => true,
            'current_warehouse_id' => $result['current_warehouse_id'],
            'current_warehouse_name' => $result['current_warehouse_name'],
        ]);
    }

    /**
     * @param string $warehouseId
     * @return JsonResponse
     */
    public function updateCurrentWarehouseWeb(string $warehouseId): JsonResponse
    {
        auth()->user()?->setCurrentWarehouse($warehouseId);

        return response()->json(['ok' => true]);
    }

    public function clearCurrentWarehouseWeb(): JsonResponse
    {
        auth()->user()?->setCurrentWarehouse(null);

        return response()->json(['ok' => true]);
    }
}
