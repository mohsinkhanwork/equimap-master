<!--begin::Header-->
<div id="kt_app_header" class="app-header bg-light">
	<!--begin::Header container-->
	<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
		<!--begin::Sidebar mobile toggle-->
		<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
			<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="fas fa-solid fa-ellipsis-vertical fs-1"></i>
            </div>
		</div>
		<!--end::Sidebar mobile toggle-->
		<!--begin::Mobile logo-->
		<div class="d-flex align-items-center flex-grow-1 d-lg-none">
			<a href="/">
                <img alt="" src="{{ image('logos/logo-text.svg') }}" class="h-30px" />
			</a>
		</div>
		<!--end::Mobile logo-->

        <div class="d-flex flex-column flex-grow-1">
            @yield('toolbar')
        </div>

		<!--begin::Header wrapper-->
		<div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1" id="kt_app_header_wrapper">
			@include(config('settings.KT_THEME_LAYOUT_DIR').'/partials/sidebar-layout/header/_navbar')
		</div>
		<!--end::Header wrapper-->
	</div>
	<!--end::Header container-->
</div>
<!--end::Header-->
