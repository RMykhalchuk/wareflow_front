@extends('layouts.admin')
@section('title', __('Мапа складу'))

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
/>

@section('content')
    <div class="page-wrap">
        <div id="warehouse-root"></div>
    </div>
@endsection

@section('page-script')
    <script type="module">
        import { initWarehouseMap } from '{{ asset('assets/js/entity/warehouse/warehouse-map/app.js') }}';

        initWarehouseMap(document.getElementById('warehouse-root'));
    </script>
@endsection
