<?php

namespace App\Services\Web\Goods;

use Illuminate\Support\Facades\DB;

class GoodsMovementHistoryService
{
    public function getHistory(string $goodsId, int $page, int $perPage): array
    {
        $incomeQuery = DB::table('income_document_leftovers as idl')
            ->join('documents as d', 'd.id', '=', 'idl.document_id')
            ->join('document_types as dt', 'dt.id', '=', 'd.type_id')
            ->join('packages as p', 'p.id', '=', 'idl.package_id')
            ->where('idl.goods_id', $goodsId)
            ->whereNull('d.deleted_at')
            ->select(
                'd.id as document_id',
                'd.local_id as document_local_id',
                'dt.name as document_type_name',
                DB::raw("'arrival' as kind"),
                DB::raw('SUM(idl.quantity * p.main_units_number) as quantity'),
                'd.created_at',
            )
            ->groupBy('d.id', 'd.local_id', 'dt.name', 'd.created_at');

        $outcomeQuery = DB::table('outcome_document_leftovers as odl')
            ->join('leftovers as l', 'l.id', '=', 'odl.leftover_id')
            ->join('documents as d', 'd.id', '=', 'odl.document_id')
            ->join('document_types as dt', 'dt.id', '=', 'd.type_id')
            ->join('packages as p', 'p.id', '=', 'odl.package_id')
            ->where('l.goods_id', $goodsId)
            ->whereNull('d.deleted_at')
            ->select(
                'd.id as document_id',
                'd.local_id as document_local_id',
                'dt.name as document_type_name',
                DB::raw("'outcome' as kind"),
                DB::raw('SUM(odl.quantity * p.main_units_number) as quantity'),
                'd.created_at',
            )
            ->groupBy('d.id', 'd.local_id', 'dt.name', 'd.created_at');

        $incomeQuery->union($outcomeQuery);

        $unionSql  = $incomeQuery->toSql();
        $bindings  = $incomeQuery->getBindings();

        $total = DB::selectOne(
            "SELECT count(*) as total FROM ({$unionSql}) as movements",
            $bindings
        )->total;

        $offset = ($page - 1) * $perPage;
        $rows   = DB::select(
            "SELECT document_id, document_local_id, document_type_name, kind, quantity
             FROM ({$unionSql}) as movements
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($bindings, [$perPage, $offset])
        );

        $data = array_map(fn($row) => [
            'document_id'        => $row->document_id,
            'document_local_id'  => (int) $row->document_local_id,
            'document_type_name' => $row->document_type_name,
            'kind'               => $row->kind,
            'quantity'           => round((float) $row->quantity, 3),
        ], $rows);

        return [
            'total' => (int) $total,
            'data'  => $data,
        ];
    }
}