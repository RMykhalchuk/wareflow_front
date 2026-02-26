<?php

namespace App\Services\Web\Workspace;

use App\Models\{Dictionaries\LegalType,
    Entities\Address\Country,
    Entities\Company\Company,
    Entities\Company\CompanyRequest,
    Entities\Company\CompanyToWorkspace,
    Entities\Integration,
    Entities\System\Workspace,
    Entities\User\UserWorkingData
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkspaceService implements WorkspaceServiceInterface
{
    public function getDashboardData(): array
    {
        $user = Auth::user();
        $user->loadMissing('workspaces.owner');
        return [
            'userToCompanyRequests' => $user->createdCompanies()->with('requests.company.workspace')->get()->pluck('requests')->flatten(),
            'workspaces' => $user->workspaces,
            'requests' => CompanyRequest::with(['company.workspace.owner'])
                ->where('user_id', $user->id)->get(),
        ];
    }

    public function getCompanyFormData(): array
    {
        return [
            'countries' => Country::all(),
            'legalTypes' => LegalType::all(),
        ];
    }

    public function getWorkspaceEditData(Workspace $workspace): array
    {
        $usersInWorkspaceCount = $workspace->usersInWorkspace()->count();
        $companies = Company::with(['users:user_id'])
            ->where(function ($query) {
                $query->whereHas('companiesInWorkspace', function ($subQuery) {
                    $subQuery->where('workspace_id', Workspace::current());
                });
            })->orWhere('workspace_id', Workspace::current())
            ->get(['id', 'workspace_id']);

        return [
            'workspace' => $workspace,
            'companiesCount' => $companies->count(),
            'usersInWorkspaceCount' => $usersInWorkspaceCount,
            'uniqueUsersCount' => $companies->pluck('users')->collapse()->unique('user_id')->count(),
            'integrations' => Integration::where('creator_company_id', $workspace->creator_company_id)->get(),
        ];
    }

    public function storeWorkspace(Request $request, Company $company): int
    {
        $request->merge(['creator_company_id' => $company->id]);
        $workspaceId = Workspace::store($request);

        if (!$company->exists) {
            $company = Company::where('creator_id', Auth::id())->latest()->first();
        }

        $company->workspace_id = $workspaceId;
        $company->save();

        CompanyToWorkspace::create([
                                       'company_id' => $company->id,
                                       'workspace_id' => $workspaceId
                                   ]);

        Auth::user()->update(['current_workspace_id' => $workspaceId]);

        UserWorkingData::withoutCreatorCompany()
            ->where('company_id', $company->id)->where('user_id', Auth::id())
            ->first()?->update(['workspace_id' => $workspaceId]);

        return $workspaceId;
    }

    public function updateWorkspace(Request $request, Workspace $workspace): int
    {
        $workspace->updateData($request);
        return $workspace->id;
    }

    public function deleteWorkspace(Workspace $workspace): void
    {
        $workspace->delete();
    }

    public function getWorkspacesList(): \Illuminate\Support\Collection
    {
        $user = Auth::user();
        $workspaces = $user->workspaces;

        if (!$user->current_workspace_id) {
            $user->update(['current_workspace_id' => $workspaces->first()?->id]);
        }

        return $workspaces;
    }

    public function changeSelectedWorkspace(int $workspaceId): void
    {
        $access = UserWorkingData::where('user_id', Auth::id())
            ->where('workspace_id', $workspaceId)
            ->exists();

        if (!$access) {
            throw new \Exception('Wrong workspace_id!');
        }

        Auth::user()->update(['current_workspace_id' => $workspaceId]);
    }

    public function calculatePrice(int $employees): int
    {
        return $employees * 200;
    }

    public function sendJoinRequest(array $payload): void
    {
        CompanyRequest::create([
                                   'user_id' => $payload['user_id'],
                                   'company_id' => $payload['company_id'],
                                   'status' => CompanyRequest::IN_PROGRESS
                               ]);
    }

    public function approveJoinRequest(array $payload): array
    {
        $companyRequest = CompanyRequest::where('user_id', $payload['user_id'])
            ->where('company_id', $payload['company_id'])->firstOrFail();

        $company = Company::findOrFail($payload['company_id']);

        DB::beginTransaction();

        try {
            $userWorkingData = UserWorkingData::create(
                [
                    'user_id' => $payload['user_id'],
                    'company_id' => $payload['company_id'],
                    'workspace_id' => $company->workspace_id,
                    'creator_company_id' => $company->creator_company_id
                ]);

            $userWorkingData->assignRole('user');

            $companyRequest->delete();

            DB::commit();

            return ['success' => true, 'data' => $userWorkingData];
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Failed to add user to the workspace: ' . $e->getMessage());
        }
    }

    public function unapproveJoinRequest(array $payload): void
    {
        CompanyRequest::where('company_id', $payload['company_id'])
            ->where('user_id', $payload['user_id'])->delete();
    }
}
