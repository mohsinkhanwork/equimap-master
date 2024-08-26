<div class="dropdown">
    <button id="menu-{{ $id }}" class="d-flex flex-row align-items-center btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
        {{ __('acp/general.layout.actions') }}
        <i class="fas fa-solid fa-angle-down fs-6 mt-1 ms-3"></i>
    </button>
    <!--begin::Menu-->
    <div aria-labelledby="menu-{{ $id }}" class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            @can('edit user')
                @if( $login_verified != 1 )
                    <form method="POST" class="em_verify_user" name="em_verify_user" action="{{ route('acp.users.verify', ['user_id' => $id ] ) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="em_delete_horse btn btn-white btn-sm">
                            Mark Verified
                        </button>
                    </form>
                @endif
            @endcan

            @can('edit user')
                    <a href="{{ route('acp.users.edit', ['user_id' => $id ] ) }}" class="btn btn-white btn-sm">
                        Edit
                    </a>
            @endcan

            @can('delete user')
                <form method="POST" name="em_delete_user" action="{{ route('acp.users.destroy', [ 'user_id' => $id ] ) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="em_delete_user btn btn-white btn-sm" {{ $id == config('general.permanent_super_admin' ) ? 'disabled="disabled"' : '' }}>
                        Delete
                    </button>
                </form>
            @endcan
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</div>
