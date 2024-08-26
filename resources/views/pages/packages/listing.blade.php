<!--begin::Wrapper-->
<div class="d-flex flex-row justify-content-between text-center mb-5 gap-5">
    <!--begin::Search-->
    <div class="d-flex flex-grow-1 align-items-center position-relative">
        <i class="fs-2 position-absolute ms-6 fa-solid fa-magnifying-glass"></i>
        <input type="text" data-table-filter="search" class="form-control form-control-solid ps-15 py-5 border-0" placeholder="Search Packages"/>
    </div>
    <!--end::Search-->

    @can('create package')
    <!--begin::Toolbar-->
    <div class="d-flex justify-content-end">
        <!--begin::Add package-->
        <a href="{{ route('acp.packages.create', ['package_type' => App\Models\Package::getDefaultPackageType() ]) }}" class="d-flex align-items-center btn btn-primary lh-xl">
            <i class="fas fa-solid fa-plus fs-2"></i>
            <span class="d-none d-md-block">Add Package</span>
        </a>
        <!--end::Add package-->
    </div>
    <!--end::Toolbar-->
    @endcan
</div>
<!--end::Wrapper-->

<!--begin::Datatable-->
<table id="kt_datatable" class="table align-middle table-row-dashed nowrap fs-6 gy-5">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th>ID</th>
            <th>Active</th>
            <th>Approved</th>
            <th>Name</th>
            <th>Package Type</th>
            <th>Price</th>
            <th>Category</th>
            <th>Sort</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">

        {!! $dataTable->table([
            'class' => 'd-none'
        ], false, false ) !!}
    </tbody>
</table>

@push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
        jQuery(function($){
            $('body').on('keyup', '*[data-table-filter="search"]', function (e) {
                let dt = window.LaravelDataTables["kt_datatable"];
                dt.search(e.target.value).draw();
            });
        });
    </script>
@endpush
<!--end::Datatable-->
