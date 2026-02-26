<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\AuthCheckPinPostRequest;
use App\Http\Requests\Api\Auth\AuthCheckPinPutRequest;
use App\Http\Requests\Api\Auth\AuthCheckUserPostRequest;
use App\Http\Requests\Web\Auth\LoginRequest;
use App\Http\Resources\Web\UserResource;
use App\Models\Entities\User\UserWorkingData;
use App\Models\User;
use App\Services\Api\Auth\PinAuthServiceInterface;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private const GUARD = 'api';

    public function __construct(
        private readonly PinAuthServiceInterface $pinAuth
    ) {}

    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth(self::GUARD)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth(self::GUARD)->user();
        $this->setGlobalCompany($user);

        return (new UserResource(auth(self::GUARD)->user(), self::GUARD))->additional(
            [
                'meta' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth(self::GUARD)->factory()->getTTL() * 60,
                ]
            ]
        );
    }

    private function setGlobalCompany(User $user): void
    {
        $workingData = User::where('users.id', $user->id)
            ->leftJoin('user_working_data', 'user_working_data.workspace_id', '=', 'users.current_workspace_id')
            ->select('user_working_data.company_id')->first();

        $companyId = $workingData?->company_id;

        if (!$companyId) {
            $workingData = UserWorkingData::where('user_id', $user->id)
                ->select('company_id')->first();

            $companyId = $workingData->company_id;
        }

        app(AuthContextService::class)->setCompanyId($companyId);
    }


    public function me()
    {
        return new UserResource(auth(self::GUARD)->user(), self::GUARD);
    }

    /**
     * Refresh JWT token
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            // Спробуємо оновити токен
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                                        'access_token' => $newToken,
                                        'token_type' => 'bearer',
                                        'expires_in' => auth('api')->factory()->getTTL() * 60,
                                        'user' => auth('api')->user()
                                    ]);

        } catch (TokenExpiredException $e) {
            return response()->json([
                                        'message' => 'Token has expired and cannot be refreshed',
                                        'error' => 'token_expired'
                                    ], 401);

        } catch (TokenInvalidException $e) {
            return response()->json([
                                        'message' => 'Token is invalid',
                                        'error' => 'token_invalid'
                                    ], 401);

        } catch (JWTException $e) {
            return response()->json([
                                        'message' => 'Token refresh failed',
                                        'error' => 'token_absent'
                                    ], 401);
        }
    }

    /**
     * @param AuthCheckUserPostRequest $request
     * @return JsonResponse
     */
    public function checkUser(AuthCheckUserPostRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->pinAuth->getUserSummaryById($data['id']);

        return response()->json([
            'ok'   => true,
            'user' => $user,
        ]);
    }

    /**
     * id,pin required params.
     *
     * @param AuthCheckPinPostRequest $request
     * @return UserResource|JsonResponse
     */
    public function checkPin(AuthCheckPinPostRequest $request): UserResource|JsonResponse
    {
        $data   = $request->validated();
        $result = $this->pinAuth->verifyPin($data['id'], $data['pin']);

        if ($result['ok'] === true) {
            /** @var User $user */
            $user = User::findOrFail($data['id']);

            $token = auth(self::GUARD)->login($user);

            $this->setGlobalCompany($user);

            return (new UserResource($user, self::GUARD))->additional([
                'meta' => [
                    'access_token' => $token,
                    'token_type'   => 'bearer',
                    'expires_in'   => auth(self::GUARD)->factory()->getTTL() * 60,
                ],
            ]);
        }

        return response()->json([
            'ok'      => false,
            'error'   => $result['error'],
            'retryIn' => $result['retryIn'] ?? null,
        ], $result['status']);
    }

    /**
     * @param AuthCheckPinPutRequest $request
     * @return JsonResponse
     */
    public function updatePin(AuthCheckPinPutRequest $request): JsonResponse
    {
        Auth::shouldUse(self::GUARD);

        $user = auth(self::GUARD)->user();

        if (!$user) {
            return response()->json(['ok' => false, 'error' => 'unauthorized'], 401);
        }

        $oldPin = (string) $request->input('old_pin');
        $newPin = (string) $request->input('pin');

        $verify = $this->pinAuth->verifyPin($user->id, $oldPin);

        if (!($verify['ok'] ?? false)) {
            return response()->json([
                'ok'      => false,
                'error'   => $verify['error'] ?? 'invalid_old_pin',
                'retryIn' => $verify['retryIn'] ?? null,
            ], $verify['status'] ?? 422);
        }

        $updated = $this->pinAuth->updatePin($user->id, $newPin);

        if ($updated['ok'] ?? false) {
            return response()->json(['ok' => true, 'message' => 'pin_updated']);
        }

        return response()->json([
            'ok'    => false,
            'error' => $updated['error'] ?? 'update_failed',
        ], $updated['status'] ?? 500);
    }

    /**
     * Refresh user.
     *
     * @return UserResource|JsonResponse
     */
    public function refreshUser(): UserResource|JsonResponse
    {
        Auth::shouldUse(self::GUARD);

        /** @var User|null $user */
        $user = auth(self::GUARD)->user();

        if (!$user) {
            return response()->json(['ok' => false, 'error' => 'unauthorized'], 401);
        }


        return new UserResource($user, self::GUARD);
    }
}
