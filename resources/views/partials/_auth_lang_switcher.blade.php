@if( isset( $available ) )
    <!--begin::Languages-->
    <div class="me-10">
        <!--begin::Toggle-->
        <button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
            <img  data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="{{ image('flags/' . $available[$current]['flag'] ) }}" alt=""/>
            <span data-kt-element="current-lang-name" class="me-1">{{ $available[$current]['title'] }}</span>
            <i class="ki-duotone ki-down fs-5 text-muted rotate-180 m-0"></i>
        </button>
        <!--end::Toggle-->

            <!--begin::Menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
                @foreach( $available as $key => $locale )
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('lang-switcher', [ 'locale' => $key ]) }}" class="menu-link d-flex px-5" data-kt-lang="{{ $locale['title'] }}">
                            <span class="symbol symbol-20px me-4">
                                <img data-kt-element="lang-flag" class="rounded-1" src="{{ image('flags/' . $locale['flag']) }}" alt=""/>
                            </span>
                            <span data-kt-element="lang-name">{{ $locale['title'] }}</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                @endforeach
            </div>
            <!--end::Menu-->
    </div>
    <!--end::Languages-->
@endif
