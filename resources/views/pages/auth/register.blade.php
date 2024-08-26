<x-auth-layout>
    <div class="w-100">
        <div class="text-center mb-11">
            <!--begin::Image-->
            <img class="theme-light-show mx-auto mw-100 w-200px w-lg-400px mb-10 mb-lg-20" src="{{ image('logos/logo-black.png') }}" alt=""/>
            <!--end::Image-->
        </div>
    </div>

    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="em_create_user" data-redirect-url="{{ route('acp.auth.verify') }}" method="post" action="{{ route('acp.auth.store') }}">
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">
                {{ __('acp/auth.register.title') }}
            </h1>
            <!--end::Title-->

            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">
                {{ __('acp/auth.register.subtitle') }}
            </div>
            <!--end::Subtitle--->
        </div>

        <!--begin::Input group--->
        <div class="fv-row mb-7">
            <!--begin::Country-->
            <select name="country" class="form-control bg-transparent" id="country" aria-label="Country" data-close-on-select="true" data-placeholder="Country" data-control="select2"  data-allow-clear="false">
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

        <!--begin::Input group--->
        <div class="fv-row mb-7">
            <!--begin::Login-->
            <input type="text" placeholder="Full Name" name="name" autocomplete="off" class="form-control bg-transparent" value="">
            <!--end::Login-->
        </div>
        <!--end::Input group--->

        <!--begin::Input group-->
        <div class="fv-row mb-7" data-kt-password-meter="true">
            <!--begin::Wrapper-->
            <div class="mb-1">
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                    <input class="form-control bg-transparent" type="password" placeholder="{{ __('acp/auth.register.fields.password') }}" name="password" autocomplete="off"/>

                    <span class="make-pass-visible btn btn-sm btn-icon position-absolute translate-middle end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="bi bi-eye-slash fs-2"></i>
                        <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                </div>
                <!--end::Input wrapper-->

                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
                <!--end::Meter-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Hint-->
            <div class="text-muted">
                {{ __('acp/auth.register.password_hint') }}
            </div>
            <!--end::Hint-->
        </div>
        <!--end::Input group--->

        <!--begin::Input group--->
        <div class="fv-row mb-7">
            <!--begin::Login-->
            <input type="password" placeholder="{{ __('acp/auth.register.fields.password_confirmation') }}" name="password_confirmation" autocomplete="off" class="form-control bg-transparent" value=""/>
            <!--end::Login-->
        </div>
        <!--end::Input group--->

        <!--begin::Accept-->
        <div class="fv-row mb-7">
            <label class="form-check form-check-inline" for="agree_terms">
                <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" value="1"/>
                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
                    {!! __('acp/auth.register.agree_terms', [
                        'business_name' => env('APP_NAME'),
                        'link_terms' => route('pages.show', [ 'page_slug' => 'terms' ] ),
                        'link_privacy' => route('pages.show', [ 'page_slug' => 'privacy' ] )
                    ]) !!}
                </span>
            </label>
        </div>
        <!--end::Accept-->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="submit_em_create_user" class="btn btn-primary">
                @include('partials/general/_button-indicator', [ 'label' => __('acp/auth.register.fields.submit') ])
            </button>
        </div>
        <!--end::Submit button-->

        <div class="text-gray-500 text-center fw-semibold fs-6">
            {{ __('acp/auth.register.already_member') }}
            <a href="{{ route('acp.auth.login') }}" class="link-primary">
                {{ __('acp/auth.register.login') }}
            </a>
        </div>
        @csrf
    </form>
    <!--end::Form-->
</x-auth-layout>
