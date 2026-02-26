<?php

use App\Broadcasting\RegistersChannel;
use App\Broadcasting\ReserveCargoRequestChannel;
use App\Broadcasting\ReserveGoodsInvoiceChannel;
use App\Broadcasting\ReserveTransportPlanningChannel;

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('registers', RegistersChannel::class);
Broadcast::channel('reserve-transport-planning', ReserveTransportPlanningChannel::class);
Broadcast::channel('reserve-goods-invoice', ReserveGoodsInvoiceChannel::class);
Broadcast::channel('reserve-cargo-request', ReserveCargoRequestChannel::class);
Broadcast::channel('reserve-goods-invoice', ReserveGoodsInvoiceChannel::class);
