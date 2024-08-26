<!--begin::User account menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
	<!--begin::Menu item-->
	<div class="menu-item px-3">
		<div class="menu-content d-flex align-items-center px-3">
			<!--begin::Avatar-->
			<div class="symbol symbol-50px me-5">
                @if( isset( $profile_image ) )
				    <img alt="" src="{{ $profile_image }}" />
                @else
                    <div class="symbol-label fs-2 fw-semibold text-primary">
                        {{ substr( $name, 0, 1 )  }}
                    </div>
                @endif
			</div>
			<!--end::Avatar-->
			<!--begin::Username-->
			<div class="d-flex flex-column">
				<div class="fw-bold d-flex align-items-center fs-5">
                    {{ $name }}
                </div>
				<span class="fw-semibold text-muted fs-7">{{ $login }}</span>
			</div>
			<!--end::Username-->
		</div>
	</div>
	<!--end::Menu item-->
	<!--begin::Menu separator-->
	<div class="separator my-2"></div>
	<!--end::Menu separator-->
	<!--begin::Menu item-->
	<div class="d-none menu-item px-5">
		<a href="{{ route('acp.users.edit', ['user_id' => auth()->id() ])  }}" class="menu-link px-5">{{ __('acp/menu.profile') }}</a>
	</div>
	<!--end::Menu item-->
	<!--begin::Menu item-->
	<div class="d-none menu-item px-5">
		<a href="{{ route('acp.users.edit', ['user_id' => auth()->id() ])  }}" class="menu-link px-5">{{ __('acp/menu.change_password') }}</a>
	</div>
	<!--end::Menu item-->
	<!--begin::Menu separator-->
	<div class="separator my-2 d-none"></div>
	<!--end::Menu separator-->

	<!--begin::Menu item-->
    @if( isset( $locales ) )
	<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
		<a href="#" class="menu-link px-5">
			<span class="menu-title position-relative">
                {{ __('acp/menu.language') }}
                <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                    {{ $locales[$current]['title'] }}
                    <img class="w-15px h-15px rounded-1 ms-2" src="{{ image('flags/' . $locales[$current]['flag'] ) }}" alt="" />
                </span>
            </span>
		</a>
		<!--begin::Menu sub-->
		<div class="menu-sub menu-sub-dropdown w-175px py-4 fs-8">
            @foreach( $locales as $code => $locale )
			<!--begin::Menu item-->
			<div class="menu-item px-3">
				<a href="{{ route('lang-switcher', [ 'locale' => $code ]) }}" class="menu-link d-flex px-5 {{ $code == $current ? 'active' : '' }}">
				<span class="symbol symbol-20px me-4">
					<img class="rounded-1" src="{{ image('flags/' . $locale['flag']) }}" alt="" />
				</span>{{ $locale['title'] }}</a>
			</div>
			<!--end::Menu item-->
            @endforeach
		</div>
		<!--end::Menu sub-->
	</div>
	<!--end::Menu item-->
    @endif

	<!--begin::Menu item-->
	<div class="menu-item px-5">
		<a href="{{ route('acp.auth.logout') }}" class="menu-link px-5">
            {{ __('acp/menu.logout') }}
        </a>
	</div>
	<!--end::Menu item-->
</div>
<!--end::User account menu-->
