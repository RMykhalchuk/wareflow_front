<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait TransportPlanningDataTrait
{
    public function scopeRelations($q)
    {
        return $q->with([
                'documents' => function ($q) {
                    $q->with(['goods.packages']);
                },
                'statuses',
                'carrier' => function ($q) {
                    $q->select(['companies.id'])->addName();
                },
                'provider' => function ($q) {
                    $q->select(['companies.id'])->addName();
                },
                'payer' => function ($q) {
                    $q->select(['companies.id'])->addName();
                },
                'driver' => function ($q) {
                    $q->select(['users.id'])->addFullName();
                },
                'transport' => function ($q) {
                    $q->select(['transports.id'])->addFullName();
                },
                'additional_equipment' => function ($q) {
                    $q->select(['additional_equipment.id', 'additional_equipment.capacity_eu'])->addFullName();
                },
            ]);
    }

    public function scopeRelationsByDate($q, $date)
    {
        return $q->with([
            'documents' => function ($q) use ($date) {
                $q->with(['goods.packages'])->where(DB::raw("(CASE WHEN CURRENT_DATE() > DATE_FORMAT(transport_planning_documents.download_start, '%Y-%m-%d') THEN DATE_FORMAT(transport_planning_documents.unloading_start, '%Y-%m-%d') ELSE DATE_FORMAT(transport_planning_documents.download_start, '%Y-%m-%d') END)"), '=', $date);
            },
            'statuses',
            'carrier' => function ($q) {
                $q->select(['companies.id'])->addName();
            },
            'driver' => function ($q) {
                $q->select(['users.id'])->addFullName();
            },
            'transport' => function ($q) {
                $q->select(['transports.id'])->addFullName();
            },
            'additional_equipment' => function ($q) {
                $q->select(['additional_equipment.id', 'additional_equipment.capacity_eu'])->addFullName();
            },
        ]);
    }

    public function scopeFilterByDocumentsDate($q, $date): void
    {
        $q->whereHas('documents', function ($q) use ($date) {
            $q->where(DB::raw("(CASE WHEN CURRENT_DATE() > DATE_FORMAT(transport_planning_documents.download_start, '%Y-%m-%d') THEN DATE_FORMAT(transport_planning_documents.unloading_start, '%Y-%m-%d') ELSE DATE_FORMAT(transport_planning_documents.download_start, '%Y-%m-%d') END)"), '=', $date);
        });
    }
}
