<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
	<!--begin::Menu wrapper-->
	<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
		<!--begin::Menu-->
		<div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
            @can('view dashboard')
                <div data-kt-menu-trigger="click" class="menu-item here show pb-5">
                    <a href="{{ route('acp.dashboard') }}">
                        <span class="menu-link">
                            <span class="menu-icon" title="{{ __('acp/menu.dashboard') }}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top">
                                <i class="fas fs-2 fa-solid fa-house"></i>
                            </span>
                            <span class="menu-title">{{ __('acp/menu.dashboard') }}</span>
                        </span>
                    </a>
                </div>
            @endcan


            @hasanyrole('super admin|admin|manager')
                <div class="menu-general pb-5">
                    <div class="menu-item">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('acp/menu.general') }}</span>
                        </div>
                    </div>

                    @can('view category')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.categories.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-brands fa-elementor"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.categories') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('view facility')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.facilities.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-circle-info"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.facilities') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endhasanyrole

            @hasanyrole('super admin|admin|vendor|manager')
                <div class="menu-management pb-5">
                    <!-- PROVIDERS & SERVICES -->
                    <div class="menu-item">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('acp/menu.management') }}</span>
                        </div>
                    </div>

                    @can('view provider')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.providers.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-magnet"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.providers') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('view service')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.services.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-gear"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.services') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('view trip')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.trips.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-suitcase"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.trips') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('view course')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.courses.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-book-bookmark"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.courses') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('view package')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.packages.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-check-double"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.packages') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('view horse')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.horses.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-horse-head"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.horses') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('view trainer')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.trainers.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-person-running"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.trainers') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endhasanyrole

            @hasanyrole('super admin|admin|manager')
            <!-- CONTENT -->
            <div class="menu-userse pb-5">
                <div class="menu-item">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('acp/menu.content') }}</span>
                    </div>
                </div>

                @can('view page')
                    <div data-kt-menu-trigger="click" class="menu-item">
                        <a href="{{ route('acp.pages.index') }}">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="fas fa-solid fa-font fs-2"></i></span>
                                <span class="menu-title">{{ __('acp/menu.pages') }}</span>
                            </span>
                        </a>
                    </div>
                @endcan

                @can('view banner')
                    <div data-kt-menu-trigger="click" class="menu-item">
                        <a href="{{ route('acp.banners.index') }}">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="fas fa-solid fa-panorama fs-2"></i></span>
                                <span class="menu-title">{{ __('acp/menu.banners') }}</span>
                            </span>
                        </a>
                    </div>
                @endcan
            </div>
            @endhasanyrole

            @hasanyrole('super admin|admin')
                <!-- USERS -->
                <div class="menu-userse pb-5">
                    <div class="menu-item">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('acp/menu.users') }}</span>
                        </div>
                    </div>

                    @can('view user')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.users.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-users"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.users') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endhasanyrole

            @hasanyrole('super admin|admin|vendor|manager')
                <div class="menu-bookings pb-5">
                    <!-- BOOKINGS -->
                    <div class="menu-item">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('acp/menu.bookings') }}</span>
                        </div>
                    </div>

                    @can('view booking')
                        <div data-kt-menu-trigger="click" class="menu-item">
                            <a href="{{ route('acp.bookings.index') }}">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fas fs-2 fa-calendar-days"></i></span>
                                    <span class="menu-title">{{ __('acp/menu.bookings') }}</span>
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endhasanyrole
        </div>
	</div>
	<!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
