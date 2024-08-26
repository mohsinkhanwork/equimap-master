<x-auth-layout>
    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="em_login_user" data-redirect-url="{{ route('acp.dashboard') }}" method="post" action="{{ route('acp.auth.login') }}">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Image-->
            <img class="theme-light-show mx-auto mw-100 w-200px w-lg-400px mb-10 mb-lg-20" src="{{ image('logos/logo-black.png') }}" alt=""/>
            <!--end::Image-->

            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">
                {{ __('acp/auth.login.title') }}
            </h1>
            <!--end::Title-->

            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">
                {{ __('acp/auth.login.subtitle') }}
            </div>
            <!--end::Subtitle--->
        </div>
        <!--begin::Heading-->

        @if( session('message') )
            <!--begin::Alert-->
            <div class="alert alert-dismissible alert-warning d-flex flex-row text-dark">
                <!--begin::Icon-->
                <i class="fas fa-solid fs-2 fa-circle-exclamation me-2 text-warning-emphasis"></i>
                <!--end::Icon-->

                <!--begin::Wrapper-->
                <div class="d-flex flex-column pe-0">
                    <!--begin::Title-->
                    <h4 class="fw-semibold text-warning-emphasis">{{ __('acp/general.notice') }}</h4>
                    <!--end::Title-->

                    <!--begin::Content-->
                    <span>{{ session('message') }}</span>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Alert-->
        @endif

        <!--begin::Input group--->
        <div class="fv-row mb-7">
            <!--begin::Country-->
            <select name="country" class="form-control bg-transparent" id="country" aria-label="Country" data-close-on-select="true" data-placeholder="Country" data-allow-clear="false">
                @foreach( $countries as $country )
                    <option data-kt-select2-country="{{ asset('assets/media/flags/' . \Illuminate\Support\Str::slug( $country->name ) . '.svg') }}" {{ $country->code == 'AE' ? 'selected="selected' : '' }} value="{{ $country->code }}" data-dialing-code="{{ $country->dialing_code }}">{{ $country->name }}</option>
                @endforeach
            </select>
            <!--end::Country-->
        </div>
        <!--end::Input group--->

        <!--begin::Input group--->
        <div class="fv-row mb-7 d-flex flex-row">
            <!--begin::Login-->
            <input type="text" class="form-control w-60px bg-transparent" disabled="disabled" name="dialing_code" id="dialing_code" value="+971" />
            <input type="text" class="form-control ms-3 flex-grow-1 bg-transparent" placeholder="{{ __('acp/auth.register.fields.login') }}" id="login" name="login" autocomplete="off" value=""/>
            <!--end::Login-->
        </div>
        <!--end::Input group--->

        <!--end::Input group--->
        <div class="fv-row mb-3">
            <!--begin::Password-->
            <input type="password" placeholder="{{ __('acp/auth.login.fields.password') }}" name="password" autocomplete="off" class="form-control bg-transparent" value=""/>
            <!--end::Password-->
        </div>
        <!--end::Input group--->

        <!--begin::Wrapper-->
        <div class="d-none d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>

            <!--begin::Link-->
            <a href="/forgot-password" class="link-primary">
                {{ __('acp/auth.login.reset') }}
            </a>
            <!--end::Link-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="submit_em_login_user" class="btn btn-primary">
                @include('partials/general/_button-indicator', ['label' => __('acp/auth.login.fields.submit') ])
            </button>
        </div>
        <!--end::Submit button-->

        <div class="text-gray-500 text-center fw-semibold fs-6">
            {{ __('acp/auth.login.not_member') }}
            <a href="{{ route('acp.auth.register') }}" class="link-primary">
                {{ __('acp/auth.login.register') }}
            </a>
        </div>
    </form>
    <!--end::Form-->

</x-auth-layout>
