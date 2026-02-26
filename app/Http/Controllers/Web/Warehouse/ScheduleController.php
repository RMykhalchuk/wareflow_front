<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Entities\Schedule\SchedulePattern;
use App\Models\Entities\System\Workspace;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use App\Services\Web\Schedule\ScheduleServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * ScheduleController.
 */
final class ScheduleController extends Controller
{
    /**
     * @var ScheduleServiceInterface
     */
    protected $scheduleService;

    /**
     * @param ScheduleServiceInterface $scheduleService
     */
    public function __construct(ScheduleServiceInterface $scheduleService)
    {
        $this->scheduleService = $scheduleService;

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
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        if (!SchedulePattern::where('name', $request->name)->first('id')) {
            SchedulePattern::create(
                [
                    'name' => $request->name,
                    'schedule' => $request->schedule,
                    'type' => $request->type,
                ]);
        }
        return response('OK');
    }

    /**
     * @param User $user
     * @return View
     */
    public function editSchedule(User $user): View
    {
        return view('user.update-schedule', $this->scheduleService->prepareEditScheduleData($user));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updateSchedule(Request $request, User $user): RedirectResponse
    {
        $this->scheduleService->updateUserSchedule($request, $user);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Warehouse $warehouse
     * @return void
     */
    public function updateWarehouseSchedule(Request $request, Warehouse $warehouse): void
    {
        $this->scheduleService->updateWarehouseSchedule($request, $warehouse);
    }

    public function storeSchedulePattern(Request $request)
    {
        if (!SchedulePattern::where('name', $request->name)->first('id'))
            SchedulePattern::create([
                                        'name' => $request->name,
                                        'schedule' => $request->schedule,
                                        'type' => $request->type
                                    ]);
        return response('OK');
    }
}
