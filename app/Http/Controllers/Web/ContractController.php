<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Contract\ContractCreateRequest;
use App\Http\Requests\Web\Contract\ContractUpdateRequest;
use App\Http\Requests\Web\Contract\DestroyContractRequest;
use App\Models\Entities\Contract\Contract;
use App\Services\Web\Contract\ContractServiceInterface;
use App\Tables\Contract\TableFacade;
use Illuminate\Http\Request;

final class ContractController extends Controller
{
    private ContractServiceInterface $contractService;

    public function __construct(ContractServiceInterface $contractService)
    {
        $this->contractService = $contractService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $contracts = $this->contractService->getAllContracts();
        return view('contract.index', compact('contracts'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        $data = $this->contractService->getContractCreationData();
        return view('contract.create', $data);
    }

    public function show(Contract $contract): \Illuminate\Contracts\View\View
    {
        $data = $this->contractService->getContractDisplayData($contract);
        return view('contract.view', $data);
    }

    public function store(ContractCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $contract = $this->contractService->createContract($request->all());
            return response()->json(['contract_id' => $contract->id]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode(422);
        }
    }

    public function filter()
    {
        return TableFacade::getFilteredData();
    }

    public function createComment(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->except(['_token']);
            $comment = $this->contractService->createComment($data);
            return response()->json(['comment_id' => $comment->id]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode(422);
        }
    }

    public function changeStatus(Request $request): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    {
        try {
            $statusId = $request->get('status_id');
            $contractId = $request->get('contract_id');
            $additionalData = $request->only(['termination_reasons', 'counterparty_regulation_id', 'decline_reasons']);

            $this->contractService->changeContractStatus($contractId, $statusId, $additionalData);
            return response('OK');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode(422);
        }
    }

    public function destroy(DestroyContractRequest $request, Contract $contract): \Illuminate\Http\JsonResponse
    {
        try {
            $this->contractService->deleteContract($contract);
            return response()->json('Successful destroy');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode(422);
        }
    }

    public function edit(Contract $contract): \Illuminate\Contracts\View\View
    {
        $data = $this->contractService->getContractEditData($contract);
        return view('contract.edit', $data);
    }

    public function update(ContractUpdateRequest $request, Contract $contract): \Illuminate\Http\JsonResponse
    {
        try {
            $updatedContract = $this->contractService->updateContract($contract, $request->all());
            return response()->json(['contract_id' => $updatedContract->id]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode(422);
        }
    }
}
