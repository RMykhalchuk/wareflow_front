<?php

namespace App\Services\Api\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * Pin Auth Service Interface.
 */
interface PinAuthServiceInterface
{
    /**
     * Returns a minimal user summary (id, name, surname) or throws ModelNotFoundException.
     */
    public function getUserSummaryById(string $id): array;

    /**
     * Verifies PIN with lockout logic.
     * Returns ['ok' => true] on success, otherwise:
     * ['ok' => false, 'status' => int, 'error' => string, 'retryIn' => int|null]
     */
    public function verifyPin(string $id, string $pin): array;

    /**
     * @param string $id
     * @param string $newPin
     * @return array
     */
    public function updatePin(string $id, string $newPin): array;
}
