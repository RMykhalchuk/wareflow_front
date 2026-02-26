<?php

use Spatie\LaravelData\Data;


class GoodsData extends Data
{
    public function __construct(
        /**
         * Name of the product
         * @example Milk 1L
         */
        public string $name,

        /**
         * Brand
         * @example Acme
         */
        public string $brand,

        /**
         * Manufacturer UUID
         */
        public string $manufacturer,

        /**
         * Provider UUID
         */
        public string $provider,

        /**
         * Expiration terms as array of integers (days)
         * @example [365, 180]
         */
        public array $expiration_date = [],

        /**
         * Batch accounting flag
         * @example true
         */
        public bool $is_batch_accounting,

        /**
         * Is weighted product
         * @example false
         */
        public bool $is_weight,

        /**
         * Net weight
         * @example 0.5
         */
        public float $weight_netto,

        /**
         * Gross weight
         * Must be >= weight_netto
         * @example 0.6
         */
        public float $weight_brutto,

        /**
         * Height in cm
         * @example 10.0
         */
        public float $height,

        /**
         * Width in cm
         * @example 5.0
         */
        public float $width,

        /**
         * Length in cm
         * @example 20.0
         */
        public float $length,

        /**
         * Temperature from (nullable)
         */
        public ?float $temp_from = null,

        /**
         * Temperature to (nullable) — must be >= temp_from
         */
        public ?float $temp_to = null,

        /**
         * Humidity from (nullable)
         */
        public ?float $humidity_from = null,

        /**
         * Humidity to (nullable) — >= humidity_from
         */
        public ?float $humidity_to = null,

        /**
         * Dustiness from (nullable)
         */
        public ?float $dustiness_from = null,

        /**
         * Dustiness to (nullable) — >= dustiness_from
         */
        public ?float $dustiness_to = null,

        /**
         * ID of measurement unit (_d_measurement_units)
         * @example 1
         */
        public int $measurement_unit_id,

        /**
         * ADR id (nullable)
         * @example 2
         */
        public ?int $adr_id = null,

        /**
         * Country id
         * @example 1
         */
        public int $manufacturer_country_id,

        /**
         * Category UUID (nullable)
         * @example uuid
         */
        public ?string $category_id = null,

        /**
         * Array of package definitions
         */
        public array $packages = [],

        /**
         * Array of product barcodes (strings)
         * @example ["0123456789012", "9876543210987"]
         */
        public array $barcodes = [],
    ) {}
}
