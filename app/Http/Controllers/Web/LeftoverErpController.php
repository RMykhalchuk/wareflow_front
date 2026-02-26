<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\LeftoverErp\LeftoverErpCrudRequest;
use App\Models\Entities\LeftoverErp\LeftoverErp;
use App\Services\Web\LeftoverErp\LeftoverErpServiceInterface;
use App\Tables\LeftoverErp\TableFacade as Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * LeftoverErpController.
 */
final class LeftoverErpController extends Controller
{
    /**
     * @var LeftoverErpServiceInterface
     */
    protected LeftoverErpServiceInterface $leftoverService;

    /**
     * @param LeftoverErpServiceInterface $leftoverService
     */
    public function __construct(LeftoverErpServiceInterface $leftoverService)
    {
        $this->leftoverService = $leftoverService;

        $this->middleware('can:view-dictionaries')->only([
            'index',
            'create',
            'store',
            'show',
            'edit',
            'update',
            'destroy',
            'filter',
        ]);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $leftoverCount = LeftoverErp::count();

        return view('leftovers-erp.index', compact('leftoverCount'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function filter(Request $request)
    {
        return Filter::getFilteredData();
    }

    /**
     * Add leftovers

     */
    public function store(LeftoverErpCrudRequest $request): JsonResponse
    {
        $leftover = $this->leftoverService->upsertOne($request->validated());

        return response()->json($leftover, 201);
    }
}
