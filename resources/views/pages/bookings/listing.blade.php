<!--begin::Wrapper-->
<div class="d-flex flex-stack mb-5">
    <!--begin::Search-->
    <div class="d-flex flex-grow-1 align-items-center position-relative my-1">
        <i class="fs-2 position-absolute ms-6 fa-solid fa-magnifying-glass"></i>
        <input type="text" data-table-filter="search" class="form-control form-control-solid ps-15 py-5 border-0" placeholder="Search Bookings"/>
    </div>
    <!--end::Search-->
</div>
<!--end::Wrapper-->

<!--begin::Datatable-->
<table id="kt_datatable" class="table align-middle table-row-dashed nowrap fs-6 gy-5">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th>ID</th>
            <th>Reference</th>
            <th>User</th>
            <th>Provider</th>
            <th>Service</th>
            <th>Check-In Time</th>
            <th>Check-Out Time</th>
            <th>Status</th>
            <th>Date</th>
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
