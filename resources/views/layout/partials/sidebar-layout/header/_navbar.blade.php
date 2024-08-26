<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0">
	<!--begin::Theme mode-->
	<div class="app-navbar-item ms-1 ms-md-3">
		@include('partials/theme-mode/_main')
	</div>
	<!--end::Theme mode-->
	<!--begin::User menu-->
	<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
		<!--begin::Menu wrapper-->
		<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            @if( isset( $profile_image ) )
                <img alt="" src="{{ $profile_image }}" />
            @else
                <div class="symbol-label fs-2 fw-semibold text-primary">
                    {{ substr( $name, 0, 1 )  }}
                </div>
            @endif
		</div>
		@include('partials/menus/_user-account-menu')
		<!--end::Menu wrapper-->
	</div>
	<!--end::User menu-->
</div>
<!--end::Navbar-->
