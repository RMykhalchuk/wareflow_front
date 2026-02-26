<?php

namespace App\Http\Resources\Web;

use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use Illuminate\Http\Resources\Json\JsonResource;

class LeftoverPackageResource extends JsonResource
{
    private ?Leftover $leftover;
    private static int $cumulative = 1;

    public function __construct($resource, ?Leftover $leftover = null)
    {
        parent::__construct($resource);
        $this->leftover = $leftover;
    }


    public function toArray($request): array
    {
        self::$cumulative *= $this->package_count ?? 1;

        return [
            'package' => [
                'id' => $this->id,
                'name' => $this->name,
            ],
            'available_quantity' => self::$cumulative  * $this->leftover->quantity,
        ];
    }

    /**
     * Рекурсивно рахує множник кількості пакувань
     */
    private function calculateMultiplier(Package $package, int $accumulator = 1): int
    {
        $accumulator *= $package->package_count ?? 1;

        // Якщо є дочірній пакет — йдемо далі вниз
        if ($package->child) {
            return $this->calculateMultiplier($package->child, $accumulator);
        }

        return $accumulator;
    }
}
