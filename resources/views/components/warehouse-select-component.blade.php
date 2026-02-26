<div>
    <x-form.select
        id="global_warehouse_id"
        name="global_warehouse_id"
        :label="''"
        :placeholder="__('localization.documents.list.select_warehouse')"
        data-id=""
        class="col-12 mb-1"
        :disabled="$disabled"
    >
        @foreach ($warehouses as $warehouse)
            <option
                value="{{ $warehouse->id }}"
                @selected($warehouse->id === $currentWarehouseId)
            >
                {{ $warehouse->name }}
            </option>
        @endforeach

        @if ($disabled)
            <option value="">{{ __('No warehouses available') }}</option>
        @endif
    </x-form.select>
</div>
