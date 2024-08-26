<div class="card mb-0">
    <div class="card-body d-flex flex-column justify-content-end py-2 p-0">
        <div class="d-flex flex-wrap flex-sm-nowrap">
            <div class="me-7 mb-4">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                    @if( isset( $provider->cover ) )
                        <img src="{{ storage_url( $provider->cover->path ) }}" class="h-100px w-auto" alt="" />
                    @else
                        <img src="{{ asset( 'assets/media/misc/horse-race.png' ) }}" class="h-100px w-auto" alt="" />
                    @endif
                </div>
            </div>
            <div class="flex-grow-1 align-items-center mt-5">
                <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                    {{ $provider->name }}
                </span>
                <a target="_blank" href="https://www.google.com/maps/place/{{ $provider->geo_loc }}" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                    <i class="fas fs-2 fa-location-dot"></i>
                    <span class="ms-2">{{ $provider->address }}</span>
                </a>
            </div>

            <div class="fs-7">
                <button id="menu-{{ $provider->id }}" class="d-flex flex-row align-items-center btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
                    {{ __('acp/general.layout.create') }}
                    <i class="fas fa-solid fa-angle-down fs-6 mt-1 ms-3"></i>
                </button>
                <div aria-labelledby="menu-{{ $provider->id }}" class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('acp.services.create', ['provider_id' => $provider->id ] ) }}" class="d-flex btn btn-white btn-sm">
                            {{ __('acp/menu.actions.provider.service') }}
                        </a>
                        <a href="{{ route('acp.trips.create',  ['provider_id' => $provider->id ] ) }}" class="d-flex btn btn-white btn-sm">
                            {{ __('acp/menu.actions.provider.trip') }}
                        </a>
                        <a href="{{ route('acp.horses.create', ['provider_id' => $provider->id ] ) }}" class="d-flex btn btn-white btn-sm">
                            {{ __('acp/menu.actions.provider.horse') }}
                        </a>
                        <a href="{{ route('acp.trainers.create', ['provider_id' => $provider->id ] ) }}" class="d-flex btn btn-white btn-sm">
                            {{ __('acp/menu.actions.provider.trainer') }}
                        </a>
                        <a href="{{ route('acp.courses.create', ['provider_id' => $provider->id ] ) }}" class="d-flex btn btn-white btn-sm">
                            {{ __('acp/menu.actions.provider.course') }}
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
            </div>
        </div>

        <div class="hover-scroll-x">
            <ul class="flex-nowrap text-nowrap nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == '' || request()->get('tab') == 'overview' ? 'active' : '' }}" href="?tab=overview">
                        Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == 'services' ? 'active' : '' }}" href="?tab=services">
                        Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == 'trips' ? 'active' : '' }}" href="?tab=trips">
                        Trips
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == 'horses' ? 'active' : '' }}" href="?tab=horses">
                        Horses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == 'trainers' ? 'active' : '' }}" href="?tab=trainers">
                        Trainers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == 'courses' ? 'active' : '' }}" href="?tab=courses">
                        Courses
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
