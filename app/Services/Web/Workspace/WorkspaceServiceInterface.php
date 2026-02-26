<?php

namespace App\Services\Web\Workspace;

interface WorkspaceServiceInterface
{
    public function getDashboardData(): array;
    public function getCompanyFormData(): array;
    public function getWorkspaceEditData(\App\Models\Entities\System\Workspace $workspace): array;
    public function storeWorkspace(\Illuminate\Http\Request $request, \App\Models\Entities\Company\Company $company): int;
    public function updateWorkspace(\App\Http\Requests\Web\Workspace\UpdateWorkspaceRequest $request, \App\Models\Entities\System\Workspace $workspace): int;
    public function deleteWorkspace(\App\Models\Entities\System\Workspace $workspace): void;
    public function getWorkspacesList(): \Illuminate\Support\Collection;
    public function changeSelectedWorkspace(int $workspaceId): void;
    public function calculatePrice(int $employees): int;
    public function sendJoinRequest(array $payload): void;
    public function approveJoinRequest(array $payload): array;
    public function unapproveJoinRequest(array $payload): void;
}
