<?php

namespace App\Services\Api\Auth;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Pin Auth Service.
 */
class PinAuthService implements PinAuthServiceInterface
{
    /**
     * @var int
     */
    protected int $maxAttempts = 5;
    /**
     * @var int
     */
    protected int $lockMinutes = 15;

    /**
     * @param string $id
     * @return array
     */
    public function getUserSummaryById(string $id): array
    {
        /** @var User|null $user */
        $user = User::query()
            ->selectRaw('id as id, name, surname')
            ->findOrFail($id);

        return [
            'id'      => $user?->id,
            'name'    => $user?->name,
            'surname' => $user?->surname,
        ];
    }

    /**
     * @param string $id
     * @param string $pin
     * @return array|true[]
     * @throws \Throwable
     */
    public function verifyPin(string $id, string $pin): array
    {
        /** @var User $user */
        $user = User::query()->findOrFail($id);

        if ($user->pin_locked_until && Carbon::now()->lt(Carbon::parse($user->pin_locked_until))) {
            $retryIn = Carbon::parse($user->pin_locked_until)->diffInSeconds(Carbon::now());

            return [
                'ok'      => false,
                'status'  => 423,
                'error'   => 'PIN locked. Try later.',
                'retryIn' => $retryIn,
            ];
        }

        $valid = $user->pin_hash && Hash::check($pin, $user->pin_hash);

        if ($valid) {
            DB::transaction(function () use ($user) {
                $user->forceFill([
                    'pin_attempts'     => 0,
                    'pin_locked_until' => null,
                ])->save();
            });

            return ['ok' => true];
        }

        $result = ['ok' => false, 'status' => 401, 'error' => 'Invalid credentials.', 'retryIn' => null];

        DB::transaction(function () use ($user, &$result) {
            $attempts = (int)($user->pin_attempts ?? 0) + 1;

            if ($attempts >= $this->maxAttempts) {
                $user->forceFill([
                    'pin_attempts'     => 0,
                    'pin_locked_until' => Carbon::now()->addMinutes($this->lockMinutes),
                ])->save();

                $result = [
                    'ok'     => false,
                    'status' => 423,
                    'error'  => "Too many attempts. PIN locked for {$this->lockMinutes} minutes.",
                    'retryIn'=> null,
                ];
            } else {
                $user->forceFill(['pin_attempts' => $attempts])->save();
            }
        });

        return $result;
    }

    /**
     * @param string $id
     * @param string $newPin
     * @return array|true[]
     * @throws \Throwable
     */
    public function updatePin(string $id, string $newPin): array
    {
        if (!preg_match('/^\d{4}$/', $newPin)) {
            return ['ok' => false, 'status' => 422, 'error' => 'invalid_pin_format'];
        }

        /** @var User $user */
        $user = User::query()->findOrFail($id);

        if ($user->pin_hash && Hash::check($newPin, $user->pin_hash)) {
            return ['ok' => false, 'status' => 422, 'error' => 'pin_same_as_old'];
        }

        DB::transaction(function () use ($user, $newPin) {
            $user->forceFill([
                'pin'              => $newPin,
                'pin_hash'         => Hash::make($newPin),
                'pin_attempts'     => 0,
                'pin_locked_until' => null,
            ])->save();
        });

        return ['ok' => true];
    }
}
