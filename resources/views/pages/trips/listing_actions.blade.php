<div class="dropdown">
    <button id="menu-{{ $id }}" class="d-flex flex-row btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
        {{ __('acp/general.layout.actions') }}
        <i class="fas fa-solid fa-angle-down fs-6 mt-1 ms-3"></i>
    </button>
    <!--begin::Menu-->
    <div aria-labelledby="menu-{{ $id }}" class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            @can('edit trip')
                <a href="{{ route('acp.trips.edit', ['trip_id' => $id ] ) }}" class="btn btn-white btn-sm">
                    Edit
                </a>
                    <br />
                <a href="{{ route('acp.trips.itinerary', ['trip_id' => $id ] ) }}" class="btn btn-white btn-sm">
                    Edit Itinerary
                </a>
            @endcan
            @can('delete trip')
                <form method="POST" class="em_delete_trip" name="em_delete_trip" action="{{ route('acp.trips.destroy', [ 'trip_id' => $id ] ) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="em_delete_trip btn btn-white btn-sm">
                        Delete
                    </button>
                </form>
            @endcan
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</div>
