<div class="dropdown">
    <button id="menu-{{ $id }}" class="d-flex flex-row btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
        {{ __('acp/general.layout.actions') }}
        <i class="fas fa-solid fa-angle-down fs-6 mt-1 ms-3"></i>
    </button>
    <!--begin::Menu-->
    <div aria-labelledby="menu-{{ $id }}" class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('acp.services.edit', ['service_id' => $id ] ) }}" class="btn btn-white btn-sm">
                Edit Service
            </a>
                <br />
            <a href="{{ route('acp.services.schedule', ['service_id' => $id ] ) }}" class="btn btn-white btn-sm">
                Edit Schedule
            </a>
                <br />
            <a href="{{ route('acp.services.horses', ['service_id' => $id ] ) }}" class="btn btn-white btn-sm">
                Manage Horses
            </a>
                <br />
            <a href="{{ route('acp.services.trainers', ['service_id' => $id ] ) }}" class="btn btn-white btn-sm">
                Manage Trainers
            </a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</div>