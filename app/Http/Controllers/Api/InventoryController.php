<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Inventory\LeftoverRequest;
use App\Http\Requests\Web\Inventory\LeftoversSyncByItemRequest;
use App\Http\Requests\Web\Leftover\LeftoverSyncRequest;
use App\Http\Resources\Api\Inventory\InventoryItemContainerResource;
use App\Http\Resources\Api\Inventory\InventoryItemRawResource;
use App\Http\Resources\Api\Inventory\InventoryItemResource;
use App\Http\Resources\Api\Inventory\InventoryResource;
use App\Http\Resources\Api\Inventory\InventoryZoneItemsResource;
use App\Models\Entities\Inventory\Inventory;
use App\Models\Entities\Inventory\InventoryItem;
use App\Models\Entities\Inventory\InventoryLeftover;
use App\Services\Terminal\Inventory\InventoryItemServiceInterface;
use App\Services\Terminal\Inventory\InventoryLeftoverServiceInterface;
use App\Services\Terminal\Inventory\InventoryServiceInterface;
use App\Tables\Inventory\Items\Leftovers\InventoryLeftoverResource;
use Dedoc\Scramble\Attributes\QueryParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Inventory Api Controller.
 */
class InventoryController extends Controller
{
    /**
     * @param InventoryServiceInterface $service
     * @param InventoryItemServiceInterface $itemService
     */
    public function __construct(
        private readonly InventoryServiceInterface $service,
        private readonly InventoryItemServiceInterface $itemService
    ) {}

    /**
     * List inventories.
     *
     * Returns filtered inventories with items data and total inventory count.
     *
     * @operationId InventoryList
     *
     * @response array{
     *     inventories: list<array{
     *         id: string,
     *         local_id: int,
     *         show_leftovers: bool,
     *         restrict_goods_movement: bool,
     *         process_cell: int,
     *         status: int,
     *         type: string,
     *         creator_id: string|null,
     *         warehouse_id: string,
     *         warehouse_erp_id: string|null,
     *         zone_id: string|null,
     *         sector_id: string|null,
     *         row_id: string|null,
     *         manufacturer_id: string|null,
     *         supplier_id: string|null,
     *         nomenclature_id: string|null,
     *         start_date: string|null,
     *         end_date: string|null,
     *         comment: string|null,
     *         created_at: string|null,
     *         updated_at: string|null,
     *         deleted_at: string|null,
     *         performer_id: string|null,
     *         cell_id: string|null,
     *         priority: int,
     *         category_subcategory: string|null,
     *         brand: string|null,
     *         erp_id: string|null,
     *         items_data: mixed
     *     }>,
     *     inventoryCount: int
     * }
     */
    #[Get(
        summary: 'List inventories',
        description: 'Returns filtered inventories with items data and total inventory count.',
        tags: ['Inventory']
    )]
    #[QueryParameter('page', description:'Page number', type:'int', default:1)]
    #[QueryParameter('per_page', description:'Items per page', type:'int', default:15)]
    #[QueryParameter('warehouse_id', description:'Warehouse UUID. Defaults to current user warehouse.', type:'string')]
    #[QueryParameter('search', description:'Search text', type:'string')]
    #[QueryParameter('sort', description:'Sort field', type:'string')]
    #[QueryParameter('sort_dir', description:'asc|desc', type:'string')]
    #[Response(200, description:'Inventories list')]
    public function index(Request $request): JsonResponse
    {
        $data = $this->service->indexDataFromRequest($request);

        return response()->json([
            'inventories'    => InventoryResource::collection($data['inventories']),
            'inventoryCount' => $data['inventoryCount'],
        ]);
    }

    /**
     * Get inventory by ID.
     *
     * Loads an inventory with optional relations defined via ?with[]=field.
     *
     * @operationId InventoryShow
     *
     * @response array{
     *     id: string,
     *     local_id: int,
     *     show_leftovers: bool,
     *     restrict_goods_movement: bool,
     *     process_cell: int,
     *     status: int,
     *     type: string,
     *     creator_id: string|null,
     *     warehouse_id: string,
     *     warehouse_erp_id: string|null,
     *     zone_id: string|null,
     *     sector_id: string|null,
     *     row_id: string|null,
     *     manufacturer_id: string|null,
     *     supplier_id: string|null,
     *     nomenclature_id: string|null,
     *     start_date: string|null,
     *     end_date: string|null,
     *     comment: string|null,
     *     created_at: string|null,
     *     updated_at: string|null,
     *     deleted_at: string|null,
     *     performer_id: string|null,
     *     cell_id: string|null,
     *     priority: int,
     *     category_subcategory: string|null,
     *     brand: string|null,
     *     erp_id: string|null
     * }
     */
    #[Get(
        summary: 'Get a single inventory',
        description: 'Returns a full inventory object with optional relations via ?with[]=relation.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory', description: 'Inventory UUID', type: 'string', format: 'uuid')]
    #[Response(200, description: 'Inventory data')]
    public function show(Request $request, Inventory $inventory): JsonResponse
    {
        return response()->json(
            new InventoryResource(
                $this->service->get(
                    (string) $inventory->id,
                    $request->input('with', [])
                )
            )
        );
    }

    /**
     * List inventory items.
     *
     * Returns paginated inventory items with zone/cell/location, status,
     * leftovers information and invented (who/when) fields.
     *
     * @operationId InventoryItemsList
     *
     * @response array{
     *     total: int,
     *     data: list<array{
     *         id: string,
     *         local_id: string,
     *         zone: string|null,
     *         cell: string|null,
     *         status: array{
     *             value: string,
     *             label: string
     *         },
     *         leftovers: array{
     *             quantity: int,
     *             id: string|null
     *         },
     *         invented: array{
     *             name: string,
     *             date: string,
     *             time: string
     *         }
     *     }>
     * }
     */
    #[Get(
        summary: 'List inventory items',
        description: 'Returns processed items for a given inventory with status, leftovers and inventor information.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory', description: 'Inventory UUID', type: 'string', format: 'uuid')]
    #[QueryParameter('page', description: 'Page number', type: 'int', default: 1)]
    #[QueryParameter('per_page', description: 'Items per page', type: 'int', default: 50)]
    #[Response(200, description: 'Inventory items list with total count')]
    public function items(Inventory $inventory, Request $request): JsonResponse
    {
        $result = $this->itemService->gridItemsDataFromRequest((string) $inventory->id, $request);

        $result['data'] = InventoryItemResource::collection(
            collect($result['data'])
        )->resolve();

        return response()->json($result);
    }

    /**
     * List inventory items by zones.
     *
     * Returns zone-based aggregation of inventory items:
     * - cell/cell-code mapping
     * - status distribution per zone
     * - inventorization progress
     *
     * @operationId InventoryZoneItems
     *
     * @response array{
     *     inventory: array{
     *         id: string,
     *         local_id: int
     *     },
     *     total: int,
     *     zones: list<array{
     *         zone: string,
     *         total: int,
     *         status_2: int,
     *         counts_by_status: array<string|int, int>,
     *         cells: list<array{
     *             inventory_item_id: string,
     *             cell: string|null,
     *             status: array{
     *                 value: int|null,
     *                 label: string
     *             }
     *         }>
     *     }>
     * }
     */
    #[Get(
        summary: 'List inventory zone items',
        description: 'Aggregates inventory items by zones and returns status counts, cells and progress.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory', description: 'Inventory UUID', type: 'string', format: 'uuid')]
    #[QueryParameter('page', description: 'Page number', type: 'int', default: 1)]
    #[QueryParameter('per_page', description: 'Items per page', type: 'int', default: 50)]
    #[Response(200, description: 'Zone-aggregated inventory items with status distribution')]
    public function itemsByZone(Inventory $inventory, Request $request): JsonResponse
    {
        return response()->json(
            new InventoryZoneItemsResource($this->itemService->gridItemsDataByZone((string) $inventory->id, $request))
        );
    }

    /**
     * Get inventory item by ID.
     *
     * Returns raw inventory item data as stored in DB.
     *
     * @operationId InventoryItemShow
     *
     * @response array{
     *     id: string,
     *     qty: int|null,
     *     real_qty: int|null,
     *     created_at: string|null,
     *     updated_at: string|null,
     *     creator_id: string,
     *     inventory_id: string,
     *     cell_id: string,
     *     update_id: string|null,
     *     status: int,
     *     area: string|null
     * }
     */
    #[Get(
        summary: 'Get a single inventory item',
        description: 'Returns a single inventory item with quantities, status, cell and metadata.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory_item', description: 'Inventory item UUID', type: 'string', format: 'uuid')]
    #[Response(200, description: 'Inventory item data')]
    public function showItem(InventoryItem $inventory_item): JsonResponse
    {
        return response()->json(new InventoryItemRawResource($this->itemService->get((string) $inventory_item->id)));
    }

    /**
     * Get item by inventory id.
     *
     * Ensures the item belongs to the given inventory and returns:
     * {
     *   "status": "ok",
     *   "data": InventoryItem
     * }
     *
     * @operationId InventoryItemShowFromInventory
     *
     * @response array{
     *     status: string,
     *     data: array{
     *         id: string,
     *         qty: int|null,
     *         real_qty: int|null,
     *         created_at: string|null,
     *         updated_at: string|null,
     *         creator_id: string,
     *         inventory_id: string,
     *         cell_id: string,
     *         update_id: string|null,
     *         status: int,
     *         area: string|null
     *     }
     * }
     */
    #[Get(
        summary: 'Get specific inventory item from inventory',
        description: 'Returns one inventory item and ensures that it belongs to the provided inventory.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory', description: 'Inventory UUID', type: 'string', format: 'uuid')]
    #[PathParameter('inventory_item', description: 'Inventory item UUID', type: 'string', format: 'uuid')]
    #[Response(200, description: 'Inventory item data with status wrapper')]
    public function showItemFromInventory(string $inventory, string $inventory_item): JsonResponse
    {
        $item = $this->itemService->showItemFromInventory($inventory, $inventory_item);

        return response()->json([
            'status' => 'ok',
            'data'   => new InventoryItemRawResource($item)->resolve(app('request')),
        ]);
    }

    /**
     * Get leftoverst by inventory.
     *
     * This returns ERP leftovers, current counted leftovers, placement,
     * manufacturer/category info, divergence, and package/measurement data.
     *
     * @operationId InventoryLeftoversList
     *
     * @response array{
     *     total: int,
     *     containers: list<array{
     *         id: string,
     *         name: string
     *     }>,
     *     data: list<array{
     *         id: string,
     *         local_id: string,
     *         leftover_id: string,
     *         real: int,
     *         name: array{
     *             title: string|null,
     *             barcode: string|null,
     *             manufacturer: string|null,
     *             category: string|null,
     *             brand: string|null
     *         },
     *         placing: array{
     *             cell_id: string|null,
     *             pallet: string,
     *             warehouse: string|null,
     *             zone: string|null,
     *             sector: string|null,
     *             row: string|null,
     *             cell: string|null,
     *             code: string
     *         },
     *         manufactured: string|null,
     *         expiry: string|null,
     *         party: string|null,
     *         condition: int,
     *         package: string|null,
     *         current_leftovers: int|null,
     *         leftovers_erp: int,
     *         divergence: string,
     *         responsible_name: string,
     *         responsible_date: string,
     *         responsible_time: string,
     *         measurement_unit: array{
     *             id: string,
     *             name: string|null
     *         }|null,
     *         package_current: array{
     *             id: string,
     *             name: string|null,
     *             qty: int|null
     *         }|null,
     *         packages_all: list<array{
     *             id: string,
     *             name: string,
     *             qty: int
     *         }>
     *     }>
     * }
     */
    #[Get(
        summary: 'List leftovers for inventory',
        description: 'Returns all leftovers (ERP + counted) for the selected inventory with placement, packaging, and discrepancy details.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory', description: 'Inventory UUID', type: 'string', format: 'uuid')]
    #[QueryParameter('page', description: 'Page number', type: 'int', default: 1)]
    #[QueryParameter('per_page', description: 'Items per page', type: 'int', default: 50)]
    #[Response(200, description: 'Inventory leftovers list with metadata')]
    public function leftoversByInventory(Inventory $inventory, Request $request): JsonResponse
    {
        $result = $this->itemService->gridLefoversDataFromRequest((string) $inventory->id, $request);

        $result['data'] = InventoryLeftoverResource::collection(
            collect($result['data'])
        )->resolve();

        return response()->json($result);
    }

    /**
     * Get leftovers by item id.
     *
     * This returns detailed leftover entries aggregated per goods,
     * with ERP quantity, current counted quantity, packaging info,
     * divergence calculation, placement and responsibility details.
     *
     * @operationId InventoryItemLeftovers
     *
     * @response array{
     *     inventory_item: array{
     *         id: string,
     *         cell: array{
     *             id: string,
     *             code: string
     *         }|null
     *     },
     *     total: int,
     *     containers: list<array{
     *         id: string,
     *         name: string
     *     }>,
     *     data: list<array{
     *         id: string,
     *         local_id: string,
     *         leftover_id: string,
     *         real: int,
     *         name: array{
     *             title: string|null,
     *             barcode: string|null,
     *             manufacturer: string|null,
     *             category: string|null,
     *             brand: string|null
     *         },
     *         placing: array{
     *             cell_id: string|null,
     *             pallet: string,
     *             warehouse: string|null,
     *             zone: string|null,
     *             sector: string|null,
     *             row: string|null,
     *             cell: string|null,
     *             code: string
     *         },
     *         manufactured: string|null,
     *         expiry: string|null,
     *         party: string|null,
     *         condition: int,
     *         package: string|null,
     *         current_leftovers: int|null,
     *         leftovers_erp: int,
     *         divergence: string,
     *         responsible_name: string,
     *         responsible_date: string,
     *         responsible_time: string,
     *         measurement_unit: array{
     *             id: string,
     *             name: string|null
     *         }|null,
     *         package_current: array{
     *             id: string,
     *             name: string|null,
     *             qty: int|null
     *         }|null,
     *         packages_all: list<array{
     *             id: string,
     *             name: string,
     *             qty: int
     *         }>
     *     }>
     * }
     */
    #[Get(
        summary: 'List leftovers for a specific inventory item',
        description: 'Returns leftovers grouped by goods for a single inventory item, including ERP vs counted comparison.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory_item', description: 'Inventory item UUID', type: 'string', format: 'uuid')]
    #[QueryParameter('page', description: 'Page number', type: 'int', default: 1)]
    #[QueryParameter('per_page', description: 'Items per page', type: 'int', default: 50)]
    #[Response(200, description: 'Detailed leftovers for a single inventory item')]
    public function leftoversByItem(InventoryItem $inventory_item, Request $request): JsonResponse
    {
        $result = $this->itemService->gridLefoversDataByItemFromRequest((string) $inventory_item->id, $request);

        $result['data'] = InventoryLeftoverResource::collection(
            collect($result['data'])
        )->resolve();

        return response()->json($result);
    }

    /**
     * Leftover store.
     *
     * @operationId InventoryLeftoverCreate
     *
     * @response 201 array{
     *     status: string,
     *     data: array{
     *         id: string
     *     },
     *     message: string
     * }
     */
    #[Post(
        summary: 'Create leftover for inventory item',
        description: 'Creates a new leftover entry linked to an inventory item.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory', description: 'Inventory item UUID', type: 'string', format: 'uuid')]

    #[BodyParameter('leftover_id', description: 'ERP leftover ID (optional)', type: 'string', nullable: true)]
    #[BodyParameter('goods_id', description: 'Goods UUID', type: 'string', format: 'uuid')]
    #[BodyParameter('packages_id', description: 'Package UUID', type: 'string', format: 'uuid')]
    #[BodyParameter('quantity', description: 'Counted quantity', type: 'int', example: 10)]
    #[BodyParameter('batch', description: 'Batch number', type: 'string', nullable: true)]
    #[BodyParameter('manufacture_date', description: 'Manufacture date (Y-m-d)', type: 'string', nullable: true)]
    #[BodyParameter('bb_date', description: 'Expiry date (Y-m-d)', type: 'string', nullable: true)]
    #[BodyParameter('container_registers_id', description: 'Container register ID', type: 'string', format: 'uuid', nullable: true)]
    #[BodyParameter('expiration_term', description: 'Expiration term (days or text)', type: 'string', nullable: true)]
    #[BodyParameter('condition', description: 'Good (1) or damaged (0)', type: 'bool', example: true)]

    #[Response(201, description: 'Leftover successfully created')]
    public function leftoversStoreApi(
        LeftoverRequest $request,
        string $inventory,
        InventoryLeftoverServiceInterface $service
    ): JsonResponse {
        $il = $service->createForItem($inventory, $request->validated());
        return response()->json([
            'status'  => 'ok',
            'data'    => ['id' => (string) $il->id],
            'message' => 'Inventory leftover created',
            ],
            201);
    }

    /**
     * Sync leftovers by item.
     *
     * Accepts an array of leftovers:
     * - If an item contains "id" → it is updated.
     * - If no "id" → a new leftover is created.
     *
     * After sync, the inventory item status becomes 2.
     *
     * @operationId InventoryItemLeftoversSync
     *
     * @response array{
     *     status: string,
     *     result: array{
     *         created_ids: list<string>,
     *         updated_ids: list<string>
     *     }
     * }
     */
    #[Post(
        summary: 'Sync leftovers for inventory item',
        description: 'Creates new leftovers or updates existing ones for a specific inventory item.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory_item', description: 'Inventory item UUID', type: 'string', format: 'uuid')]
    #[BodyParameter('items', description: 'Array of leftover entries to sync', type: 'array')]
    #[BodyParameter('items.*.id', description: 'Leftover ID (if present → update operation)', type: 'string', nullable: true)]
    #[BodyParameter('items.*.leftover_id', description: 'ERP leftover ID (optional)', type: 'string', nullable: true)]
    #[BodyParameter('items.*.goods_id', description: 'Goods UUID', type: 'string', format: 'uuid')]
    #[BodyParameter('items.*.packages_id', description: 'Package UUID', type: 'string', format: 'uuid', nullable: true)]
    #[BodyParameter('items.*.quantity', description: 'Counted quantity', type: 'int', example: 5)]
    #[BodyParameter('items.*.current_leftovers', description: 'Actual counted leftovers (auto-set from quantity if missing)', type: 'int', nullable: true)]
    #[BodyParameter('items.*.batch', description: 'Batch number', type: 'string', nullable: true)]
    #[BodyParameter('items.*.manufacture_date', description: 'Date of manufacturing (Y-m-d)', type: 'string', nullable: true)]
    #[BodyParameter('items.*.bb_date', description: 'Expiry date (Y-m-d)', type: 'string', nullable: true)]
    #[BodyParameter('items.*.container_registers_id', description: 'Container register ID', type: 'string', nullable: true)]
    #[BodyParameter('items.*.expiration_term', description: 'Expiration term (string or days)', type: 'string', nullable: true)]
    #[BodyParameter('items.*.condition', description: 'Goods condition (0|1)', type: 'bool', nullable: true)]
    #[Response(200, description: 'Successfully synchronized leftovers for the inventory item')]
    public function syncLeftoversByItem(
        LeftoversSyncByItemRequest $request,
        string $inventory_item,
        InventoryLeftoverServiceInterface $service
    ): JsonResponse {
        $result = $service->syncByInventoryItem($request->validated('items'), $inventory_item);

        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

    /**
     * Change leftover quantity.
     *
     * Updates the "current_leftovers" field of a leftover and returns:
     * - ERP quantity (from leftover_id or quantity field)
     * - current counted quantity
     * - divergence (+/-)
     *
     * @operationId InventoryLeftoverCorrectQuantity
     *
     * @response array{
     *     status: string,
     *     item_id: string,
     *     current_leftovers: int,
     *     leftovers_erp: int,
     *     divergence: string
     * }
     */
    #[Patch(
        summary: 'Correct leftover quantity',
        description: 'Updates the counted quantity ("current_leftovers") for a leftover entry.',
        tags: ['Inventory']
    )]
    #[PathParameter('leftovers', description: 'Leftover UUID', type: 'string', format: 'uuid')]

    #[BodyParameter('quantity', description: 'New counted quantity', type: 'number', example: 12.0)]

    #[Response(200, description: 'Corrected leftover quantity and recalculated divergence')]
    public function correctLeftoverQuantity(
        Request $request,
        InventoryLeftover $leftover,
        InventoryLeftoverServiceInterface $leftoverService
    ): JsonResponse
    {
        return response()->json(
            $leftoverService->correctCurrent($leftover->id, (float) $request->get('quantity'))
        );
    }

    /**
     * Sync manual leftovers.
     *
     * Accepts a list of leftovers to create and a list to update.
     * All items are processed inside a single transaction.
     *
     * @operationId InventoryLeftoversGlobalSync
     *
     * @response 200 array{
     *     created_count: int,
     *     updated_count: int
     * }
     */
    #[Post(
        summary: 'Bulk sync leftovers',
        description: 'Creates and updates leftovers in a single transaction.',
        tags: ['Inventory']
    )]

    #[BodyParameter('leftovers', description: 'Object containing create and update arrays', type: 'object')]

    #[BodyParameter('leftovers.create', description: 'Leftovers to be created', type: 'array', nullable: true)]
    #[BodyParameter('leftovers.create.*.goods_id', description: 'Goods UUID', type: 'string', format: 'uuid')]
    #[BodyParameter('leftovers.create.*.packages_id', description: 'Package UUID', type: 'string', format: 'uuid', nullable: true)]
    #[BodyParameter('leftovers.create.*.quantity', description: 'Quantity', type: 'int')]
    #[BodyParameter('leftovers.create.*.batch', description: 'Batch number', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.create.*.manufacture_date', description: 'Manufacture date Y-m-d', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.create.*.bb_date', description: 'Expire date Y-m-d', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.create.*.container_registers_id', description: 'Container register', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.create.*.expiration_term', description: 'Expiration term (string/days)', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.create.*.condition', description: 'Boolean condition flag', type: 'bool', nullable: true)]
    #[BodyParameter('leftovers.create.*.leftover_id', description: 'ERP leftover reference', type: 'string', nullable: true)]

    #[BodyParameter('leftovers.update', description: 'Leftovers to update', type: 'array', nullable: true)]
    #[BodyParameter('leftovers.update.*.id', description: 'Leftover ID to update', type: 'string', format: 'uuid')]
    #[BodyParameter('leftovers.update.*.goods_id', description: 'Goods UUID', type: 'string', format: 'uuid', nullable: true)]
    #[BodyParameter('leftovers.update.*.packages_id', description: 'Package UUID', type: 'string', format: 'uuid', nullable: true)]
    #[BodyParameter('leftovers.update.*.quantity', description: 'Quantity', type: 'int', nullable: true)]
    #[BodyParameter('leftovers.update.*.batch', description: 'Batch number', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.update.*.manufacture_date', description: 'Manufacture date', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.update.*.bb_date', description: 'Expiry date', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.update.*.expiration_term', description: 'Expiration term', type: 'string', nullable: true)]
    #[BodyParameter('leftovers.update.*.condition', description: 'Goods condition flag', type: 'bool', nullable: true)]

    #[Response(
        status: 200,
        description: 'Counts of newly created and updated leftovers'
    )]
    public function syncLeftovers(
        LeftoverSyncRequest $request,
        InventoryLeftoverServiceInterface $leftoverService
    ): JsonResponse {
        $leftoversData = $request->validated()['leftovers'] ?? [];

        return response()->json($leftoverService->leftoversSync($leftoversData));
    }

    /**
     * Get containers with leftovers.
     *
     * Returns all containers that include leftovers associated with the given
     * inventory item, including leftover metadata, goods data, package details,
     * responsible user, divergence and full placement hierarchy.
     *
     * @operationId InventoryItemContainersWithLeftovers
     *
     * @response array{
     *     total: int,
     *     data: list<array{
     *         id: string,
     *         uuid: string,
     *         created_at: string|null,
     *         updated_at: string|null,
     *         leftovers_count: int,
     *         leftovers: list<array{
     *             id: string,
     *             leftover_id: string|null,
     *             real: int,
     *             name: array{
     *                 title: string|null,
     *                 barcode: string|null,
     *                 manufacturer: string|null,
     *                 category: string|null,
     *                 brand: string|null
     *             },
     *             placing: array{
     *                 cell_id: string|null,
     *                 pallet: string,
     *                 warehouse: string|null,
     *                 zone: string|null,
     *                 sector: string|null,
     *                 row: string|null,
     *                 cell: string|null,
     *                 code: string
     *             },
     *             manufactured: string|null,
     *             expiry: string|null,
     *             party: string|null,
     *             condition: int|null,
     *             package: string|null,
     *             current_leftovers: int|null,
     *             leftovers_erp: int,
     *             divergence: string,
     *             responsible_name: string,
     *             responsible_date: string,
     *             responsible_time: string,
     *             measurement_unit: array{
     *                 id: string,
     *                 name: string|null
     *             }|null,
     *             package_current: array{
     *                 id: string,
     *                 name: string|null,
     *                 qty: int|null
     *             }|null,
     *             packages_all: list<array{
     *                 id: string,
     *                 name: string,
     *                 qty: int
     *             }>
     *         }>
     *     }>
     * }
     */
    #[Get(
        summary: 'List containers with leftovers for an inventory item',
        description: 'Returns containers linked to an inventory item and all leftover rows inside each container.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory_item', description: 'Inventory item UUID', type: 'string', format: 'uuid')]
    #[Response(200, description: 'Containers and leftover rows grouped by container')]
    public function containersWithLeftoversByItem(
        InventoryItem $inventory_item,
        InventoryServiceInterface $service
    ): JsonResponse {
        $result = $service->getContainersWithLeftoversByItem($inventory_item->id);

        $result['data'] = InventoryItemContainerResource::collection(
            collect($result['data'])
        )->resolve();

        return response()->json($result);
    }

    /**
     * Reset inventory item.
     *
     * Clears item area, resets status to 1, clears areas on all related leftovers,
     * resets current_leftovers for leftovers linked to ERP, and deletes new (non-ERP) leftover rows.
     *
     * @operationId InventoryItemReset
     *
     * @response array{
     *     status: string,
     *     item_id: string,
     *     effects: array{
     *         inventory_item_updated: bool,
     *         leftovers_area_cleared: int,
     *         leftovers_reset_with_leftover: int,
     *         leftovers_deleted_new_rows: int
     *     },
     *     timestamp: string
     * }
     */
    #[Patch(
        summary: 'Reset inventory item and its leftovers',
        description: 'Resets inventory item status/area and clears or deletes related leftovers according to business rules.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory_item', description: 'Inventory item UUID', type: 'string', format: 'uuid')]
    #[Response(200, description: 'Reset result with affected rows counters')]
    public function reset(string $inventory_item, InventoryItemServiceInterface $service): JsonResponse
    {
        return response()->json($service->reset($inventory_item));
    }

    /**
     * Get leftovers with container by item.
     *
     * Returns paginated leftovers with goods info, placement, ERP comparison,
     * divergence, responsible user details and packaging data.
     *
     * @operationId InventoryItemLeftoversByContainer
     *
     * @response array{
     *     total: int,
     *     data: list<array{
     *         id: string,
     *         local_id: string,
     *         leftover_id: string,
     *         real: int,
     *         name: array{
     *             title: string|null,
     *             barcode: string|null,
     *             manufacturer: string|null,
     *             category: string|null,
     *             brand: string|null
     *         },
     *         placing: array{
     *             cell_id: string|null,
     *             pallet: string,
     *             warehouse: string|null,
     *             zone: string|null,
     *             sector: string|null,
     *             row: string|null,
     *             cell: string|null,
     *             code: string
     *         },
     *         manufactured: string|null,
     *         expiry: string|null,
     *         party: string|null,
     *         condition: int|string|null,
     *         package: string|null,
     *         current_leftovers: int|null,
     *         leftovers_erp: int,
     *         divergence: string,
     *         responsible_name: string,
     *         responsible_date: string,
     *         responsible_time: string,
     *         measurement_unit: array{
     *             id: string,
     *             name: string|null
     *         }|null,
     *         package_current: array{
     *             id: string,
     *             name: string|null,
     *             qty: int|null
     *         }|null,
     *         packages_all: list<array{
     *             id: string,
     *             name: string,
     *             qty: int
     *         }>
     *     }>
     * }
     */
    #[Get(
        summary: 'Leftovers inside a container for an inventory item',
        description: 'Returns paginated leftovers located inside a given container and linked to an inventory item.',
        tags: ['Inventory']
    )]
    #[PathParameter('inventory_item', description: 'Inventory item UUID', type: 'string', format: 'uuid')]
    #[PathParameter('container', description: 'Container register UUID', type: 'string', format: 'uuid')]
    #[QueryParameter('per_page', description: 'Items per page', type: 'int', default: 50)]
    #[QueryParameter('page', description: 'Page number', type: 'int', default: 1)]
    #[Response(200, description: 'Paginated leftovers inside a container for an inventory item')]
    public function leftoversByItemAndContainer(
        string $inventory_item,
        string $container,
        Request $r,
        InventoryItemServiceInterface $itemService
    ): JsonResponse {
        $result = $itemService->getLeftoversByItemAndContainer(
            $inventory_item,
            $container,
            (int) $r->input('per_page', 50),
            (int) $r->input('page', 1)
        );

        $result['data'] = InventoryLeftoverResource::collection(
            collect($result['data'])
        )->resolve();

        return response()->json($result);
    }
}
