<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Entities\Integration;
use Illuminate\Http\Request;

final class IntegrationController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $integrationId = Integration::store($request);

        return response()->json(['integration_id' => $integrationId]);
    }

    public function update(Request $request, Integration $integration): \Illuminate\Http\JsonResponse
    {
        $integration->fill($request->except(['_token']));
        $integration->save();

        return response()->json(['integration_id' => $integration->id]);
    }

    public function destroy(Integration $integration): \Illuminate\Http\JsonResponse
    {
        $integration->delete();

        return response()->json([], 201);
    }
}
