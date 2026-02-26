<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Services\Web\Address\AddressService;
use Illuminate\Http\Resources\Json\JsonResource;

final class AddressController extends Controller
{
    public function __construct(private AddressService $addressService)
    {
    }

    public function street(): JsonResource
    {
        return JsonResource::make($this->addressService->getStreets());
    }

    public function settlement(): JsonResource
    {
        return JsonResource::make($this->addressService->getSettlements());
    }
}
