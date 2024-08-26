<div class="dropdown">
    <button id="menu-{{ $id }}" class="d-flex flex-row btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
        {{ __('acp/general.layout.actions') }}
        <i class="fas fa-solid fa-angle-down fs-6 mt-1 ms-3"></i>
    </button>
    <!--begin::Menu-->
    <div aria-labelledby="menu-{{ $id }}" class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            @can('edit banner')
                <a href="{{ route('acp.banners.edit', ['banner_id' => $id ] ) }}" class="btn btn-white btn-sm">
                    {{ __('acp/general.edit_button') }}
                </a>
            @endcan
            @can('delete banner')
                <form method="POST" class="em_delete_banner" name="em_delete_banner" action="{{ route('acp.banners.destroy', [ 'banner_id' => $id ] ) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="em_delete_page btn btn-white btn-sm">
                        {{ __('acp/general.delete_button') }}
                    </button>
                </form>
            @endcan
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</div>
