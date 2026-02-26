<?php

use Spatie\LaravelData\Data;

class PackageData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $parent_id = null,
        public string $name,
        public string $barcode,
        public float $main_units_number,
        public ?int $package_count = null,
        public float $weight_netto,
        public float $weight_brutto,
        public float $height,
        public float $width,
        public float $length,
    ) {}
}
