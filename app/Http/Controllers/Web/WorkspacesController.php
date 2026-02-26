<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Workspace\UpdateWorkspaceRequest;
use App\Http\Resources\Web\WorkspaceResource;
use App\Models\Entities\Company\Company;
use App\Models\Entities\System\Workspace;
use App\Services\Web\Workspace\WorkspaceServiceInterface;
use Illuminate\Http\Request;

final class WorkspacesController extends Controller
{
    public function index(WorkspaceServiceInterface $service): \Illuminate\Contracts\View\View
    {
        return view('workspaces.workspaces-list', $service->getDashboardData());
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('workspaces.create');
    }

    public function createCompany(WorkspaceServiceInterface $service): \Illuminate\Contracts\View\View
    {
        return view('workspaces.create-company', $service->getCompanyFormData());
    }

    public function edit(Workspace $workspace, WorkspaceServiceInterface $service): \Illuminate\Contracts\View\View
    {
        return view('workspaces.workspace-settings', $service->getWorkspaceEditData($workspace));
    }

    public function store(Request $request, Company $company, WorkspaceServiceInterface $service): \Illuminate\Http\JsonResponse
    {
        $workspaceId = $service->storeWorkspace($request, $company);
        return response()->json(['workspace_id' => $workspaceId]);
    }

    public function update(UpdateWorkspaceRequest $request, Workspace $workspace, WorkspaceServiceInterface $service): \Illuminate\Http\JsonResponse
    {
        $workspaceId = $service->updateWorkspace($request, $workspace);
        return response()->json(['workspace_id' => $workspaceId]);
    }

    public function destroy(Workspace $workspace, WorkspaceServiceInterface $service): \Illuminate\Http\JsonResponse
    {
        $service->deleteWorkspace($workspace);
        return response()->json([], 201);
    }

    public function getWorkspacesList(WorkspaceServiceInterface $service): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return WorkspaceResource::collection($service->getWorkspacesList());
    }

    public function changeSelectedWorkspace(Request $request, WorkspaceServiceInterface $service): \Illuminate\Http\JsonResponse
    {
        $request->validate(['workspace_id' => 'required|exists:workspaces,id']);
        try {
            $service->changeSelectedWorkspace($request->workspace_id);
            return response()->json([], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function getPrice(Request $request, WorkspaceServiceInterface $service): \Illuminate\Http\JsonResponse
    {
        $request->validate(['employees' => ['integer', 'gt:0']]);
        $sum = $service->calculatePrice($request->employees);
        return response()->json(['sum' => $sum]);
    }

    public function sendJoinRequest(Request $request, WorkspaceServiceInterface $service): \Illuminate\Http\Response
    {
        $service->sendJoinRequest($request->only(['user_id', 'company_id']));
        return response('OK');
    }

    public function approveUserToWorkspace(Request $request, WorkspaceServiceInterface $service): \Illuminate\Http\Response
    {
        try {
            $result = $service->approveJoinRequest($request->only(['user_id', 'company_id']));
            return response($result);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function unapproveUserToWorkspace(Request $request, WorkspaceServiceInterface $service): \Illuminate\Http\Response
    {
        $service->unapproveJoinRequest($request->only(['user_id', 'company_id']));
        return response('OK');
    }
}
