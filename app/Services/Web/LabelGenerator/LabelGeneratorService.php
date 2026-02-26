<?php

namespace App\Services\Web\LabelGenerator;


use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LabelGeneratorService
{
    private const CHUNK_SIZE = 300;

    /**
     * Генерує PDF-файли з етикетками.
     * Великі набори діляться на частини по CHUNK_SIZE — повертає масив шляхів до файлів.
     *
     * @return string[]
     */
    public function generateCellLabels(array $data): array
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $type  = $data['type'];
        $items = $this->buildItems($data);

        if (empty($items)) {
            return [];
        }

        if ($type !== 'container' && $type !== 'cell') {
            $type = 'cell';
        }

        $chunks = array_chunk($items, self::CHUNK_SIZE);

        $filePaths = [];
        foreach ($chunks as $chunk) {
            $filePaths[] = $this->generatePdf($type, $chunk);
        }

        return $filePaths;
    }

    /**
     * Будує повний список items незалежно від типу вхідних даних.
     */
    protected function buildItems(array $data): array
    {
        $type = $data['type'];

        if (!empty($data['items'])) {
            return $this->generateItems($type, $data['items']);
        }

        if (!empty($data['item_from']) && !empty($data['item_to'])) {
            return $this->generateRangeItems($type, $data['item_from'], $data['item_to']);
        }

        return [];
    }

    /**
     * Генерує один PDF для переданого масиву items.
     */
    protected function generatePdf(string $type, array $items): string
    {
        $size = [0, 0, 283.46, 170.08]; // 100x60mm

        $html = $this->renderTemplate($type, $items);

        $pdf = Pdf::loadHTML($html)
            ->setPaper($size)
            ->setOptions(['isRemoteEnabled' => true]);

        $fileName = 'labels/' . now()->format('Ymd_His') . '_' . Str::random(6) . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        return $fileName;
    }

    protected function generateRangeItems(string $type, string $fromId, string $toId): array
    {
        switch ($type) {
            case 'cell':
                $fromCode = Cell::findOrFail($fromId)->code;
                $toCode   = Cell::findOrFail($toId)->code;

                $cells = Cell::whereBetween('code', [min($fromCode, $toCode), max($fromCode, $toCode)])
                    ->where('parent_type', 'row')
                    ->orderBy('code', 'asc');

                return $this->mapCells($this->cellsWithEagerLoading($cells));

            case 'zone':
                $from = WarehouseZone::findOrFail($fromId);
                $to   = WarehouseZone::findOrFail($toId);

                $zoneIds = WarehouseZone::whereBetween('created_at', [min($from->created_at, $to->created_at), max($from->created_at, $to->created_at)])
                    ->pluck('id');

                $rowIds = Row::whereIn('sector_id', Sector::whereIn('zone_id', $zoneIds)->pluck('id'))
                    ->pluck('id');

                return $this->mapCells($this->cellsInRows($rowIds));

            case 'sector':
                $from = Sector::findOrFail($fromId);
                $to   = Sector::findOrFail($toId);

                $sectorIds = Sector::whereBetween('created_at', [min($from->created_at, $to->created_at), max($from->created_at, $to->created_at)])
                    ->pluck('id');

                $rowIds = Row::whereIn('sector_id', $sectorIds)->pluck('id');

                return $this->mapCells($this->cellsInRows($rowIds));

            case 'row':
                $from = Row::findOrFail($fromId);
                $to   = Row::findOrFail($toId);

                $rowIds = Row::whereBetween('created_at', [min($from->created_at, $to->created_at), max($from->created_at, $to->created_at)])
                    ->pluck('id');

                return $this->mapCells($this->cellsInRows($rowIds));
        }

        return [];
    }

    protected function generateItems(string $type, array $items): array
    {
        switch ($type) {
            case 'cell':
            case 'zone':
            case 'sector':
                $cells = $this->cellsWithEagerLoading(Cell::whereIn('id', $items));
                return $this->mapCells($cells);

            case 'container':
                $containers = ContainerRegister::whereIn('id', $items)
                    ->with(['container.type'])
                    ->get();

                return $containers->map(function (ContainerRegister $containerRegister) {
                    $parent = $containerRegister->container;
                    $type   = $parent->type;

                    return [
                        'name'        => $parent['name'] ?? '-',
                        'code_format' => $parent['code_format'] ?? '-',
                        'code'        => substr($containerRegister['code'] ?? '', -8),
                        'id'          => $containerRegister->id,
                        'type_name'   => $type['name'] ?? '-',
                        'qr'          => $this->generateQRCode('container/' . $containerRegister->id),
                    ];
                })->toArray();
        }

        return [];
    }

    protected function renderTemplate(string $type, array $labels): string
    {
        $svgPath    = public_path('assets/icons/entity/stickers/logo.svg');
        $logoBase64 = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($svgPath));

        return view("stickers.pdf-templates.$type", compact('labels', 'logoBase64'))->render();
    }

    protected function mapCells($cells): array
    {
        return $cells->map(function (Cell $cell) {
            $allocation = $cell->allocation;
            return [
                'sector'    => $allocation['sector_code'] ?? '-',
                'zone'      => $allocation['zone'] ?? '-',
                'warehouse' => $allocation['warehouse'] ?? '-',
                'code'      => $cell->code,
                'id'        => $cell->id,
                'qr'        => $this->generateQRCode('cell/' . $cell->id),
            ];
        })->toArray();
    }

    protected function generateQRCode(string $code): string
    {
        $svg = QrCode::format('svg')->encoding('UTF-8')->size(250)->generate($code);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function cellsInRows($rowIds)
    {
        return $this->cellsWithEagerLoading(
            Cell::where('parent_type', 'row')->whereIn('model_id', $rowIds)->orderBy('code', 'asc')
        );
    }

    private function cellsWithEagerLoading($query)
    {
        return $query->with([
            'parent' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    WarehouseZone::class => ['warehouse.location'],
                    Sector::class        => ['zone.warehouse.location'],
                    Row::class           => ['sector.zone.warehouse.location'],
                ]);
            },
        ])->get();
    }
}