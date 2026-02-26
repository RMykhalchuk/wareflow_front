<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Entities\Contract\Regulation;
use Illuminate\Http\Request;

final class RegulationController extends Controller
{
    //TODO Need refactor
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('regulation.index');
    }

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $regulationType = $request->get('type');
        $serviceSide = $request->get('service_side');
        $withoutTrashed = $request->get('without_trashed');
        $withoutDraft = $request->get('without_draft');
        $name = $request->get('name');

        $regulations = Regulation::addContractsCount()
            ->where('type', $regulationType)
            ->where('service_side', $serviceSide);

        if (!$withoutTrashed) {
            $regulations = $regulations->withTrashed();
        }

        if (!$withoutDraft) {
            $regulations = $regulations->where('draft', 0);
        }

        if ($name) {
            $regulations->where(function ($q) use ($name) {
                $q->where('name', 'like', "%$name%")
                    ->orWhereHas('descendants', function ($q) use ($name) {
                        $q->where('name', 'like', "%$name%");
                    });
            });
        }

        $regulations = $regulations->select('*')->addContractsCount()->get();

        return response()->json(['regulations' => $regulations->toTree()]);
    }

    public function getList(Request $request): \Illuminate\Http\JsonResponse
    {
        $regulationType = $request->get('type');
        $serviceSide = $request->get('service_side');

        $regulations = Regulation::where('type', $regulationType)
            ->where('service_side', $serviceSide)
            ->where('draft', 0)
            ->whereNull('parent_id')
            ->get();

        return response()->json(['regulations' => $regulations]);
    }

    public function show(Regulation $regulation): \Illuminate\Http\JsonResponse
    {
        return response()->json(['regulation' => $regulation, 'parentName' => $regulation->parent->name ?? null]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $regulationId = Regulation::store($request);

        return response()->json(['regulation_id' => $regulationId]);
    }

    public function update(Request $request, Regulation $regulation): \Illuminate\Http\JsonResponse
    {
        $data = $request->except(['_method', '_token', 'change_descendants']);

        if ($request->get('change_descendants')) {
            $descendantsAndSelfIds = $regulation->descendants()->withTrashed()->pluck('id');

            Regulation::whereIn('id', $descendantsAndSelfIds)->update(['settings' => $data['settings']]);
        }

        $regulation->fill($data);
        $regulation->save();

        Regulation::fixTree();

        return response()->json(['regulation_id' => $regulation->id]);
    }

    public function archive(Request $request, Regulation $regulation): \Illuminate\Http\JsonResponse
    {
        //uncomment after improve contracts

        $descendantsAndSelfIds = $regulation->descendants()->withTrashed()->pluck('id');
        $descendantsAndSelfIds->push($regulation->id);

        /*$countContracts = Contract::where(function ($q) use ($descendantsAndSelfIds) {
            $q->whereIn('company_regulation_id', $descendantsAndSelfIds)
                ->orWhereIn('counterparty_regulation_id', $descendantsAndSelfIds);
        })->count();

        if ($countContracts) {
            return response()->json([
                'message' => 'Exists contracts in current or descendants regulations',
                'count' => $countContracts
            ], 422);
        }*/

        if (is_null($regulation->deleted_at)) {
            Regulation::whereIn('id', $descendantsAndSelfIds)->delete();
        } else {
            Regulation::whereIn('id', $descendantsAndSelfIds)->restore();
        }

        return response()->json([], 201);
    }

    public function destroy(Request $request, Regulation $regulation): \Illuminate\Http\JsonResponse
    {
        //uncomment after improve contracts
        /*
        $descendantsAndSelfIds = $regulation->descendants()->withTrashed()->pluck('id');
        $descendantsAndSelfIds->push($regulation->id);

        $countContracts = Contract::where(function ($q) use ($descendantsIds) {
            $q->whereIn('company_regulation_id', $descendantsIds)
                ->orWhereIn('counterparty_regulation_id', $descendantsIds);
        })->count();

        if ($countContracts) {
            return response()->json([
                'message' => 'Exists contracts in current or descendants regulations',
                'count' => $countContracts
            ], 422);
        }*/

        if (count($regulation->descendants)) {
            return response()->json([
                'message' => 'Exists descendants'
            ], 422);
        }

        $regulation->forceDelete();

        return response()->json([], 201);
    }

    public function duplicate(Request $request, Regulation $regulation): \Illuminate\Http\JsonResponse
    {
        $descendants = $regulation->descendants;

        $newRegulation = $regulation->replicate();
        $newRegulation->parent_id = $regulation->parent_id;
        $newRegulation->push();

        if (count($descendants)) {
            foreach ($descendants as $descendant) {
                $newDescendant = $descendant->replicate();
                $newDescendant->parent_id = $newRegulation->id;
                $newDescendant->push();
            }
        }

        Regulation::fixTree();

        return response()->json(['regulation_id' => $newRegulation->id]);
    }
}
