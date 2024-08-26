<x-auth-layout>
    <div class="w-100">
        <div class="text-center mb-11">
            <!--begin::Image-->
            <img class="theme-light-show mx-auto mw-100 w-200px w-lg-400px mb-10 mb-lg-20" src="{{ image('logos/logo-black.png') }}" alt=""/>
            <!--end::Image-->
        </div>
    </div>
    <!--begin::OTP Form-->
    <form class="form w-100" novalidate="novalidate" id="em_verify_user" data-redirect-url="{{ route('acp.auth.login') }}" method="post" action="{{ route('acp.auth.verify_user') }}">
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3 form-title">
                {{ __('acp/auth.verify.title') }}
            </h1>
            <!--end::Title-->

            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6 form-subtitle">
                {{ __('acp/auth.verify.subtitle', [ 'phone_number' => $user->login ]) }}
            </div>
            <!--end::Subtitle--->
        </div>

        <!--begin::Alert-->
        <div class="d-none alert alert-dismissible alert-danger d-flex flex-row text-dark">
            <!--begin::Icon-->
            <i class="fas fa-solid fs-2 fa-circle-exclamation me-2 text-danger-emphasis"></i>
            <!--end::Icon-->

            <!--begin::Wrapper-->
            <div class="d-flex flex-column pe-0">
                <!--begin::Title-->
                <h4 class="fw-semibold text-danger-emphasis">{{ __('acp/general.notice') }}</h4>
                <!--end::Title-->

                <!--begin::Content-->
                <span>{{ session('message') }}</span>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Alert-->

        <div class="d-flex flex-row align-items-center">
            <input type="text" name="code[]" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent text-center mx-1 my-2 otp-field" value="" inputmode="text">
            <input type="text" name="code[]" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent text-center mx-1 my-2 otp-field" value="" inputmode="text">
            <input type="text" name="code[]" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent text-center mx-1 my-2 otp-field" value="" inputmode="text">
            <input type="text" name="code[]" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent text-center mx-1 my-2 otp-field" value="" inputmode="text">
            <input type="text" name="code[]" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent text-center mx-1 my-2 otp-field" value="" inputmode="text">
            <input type="text" name="code[]" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent text-center mx-1 my-2 otp-field" value="" inputmode="text">
        </div>

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" data-kt-indicator="on" disabled="disabled" id="submit_em_verify_user" class="btn btn-primary">
                @include('partials/general/_button-indicator', [ 'label' => __('acp/auth.verify.fields.submit') ])
            </button>
        </div>
        <!--end::Submit button-->

        <input type="hidden" name="login" value="{{ $user->login }}" />
        <input type="hidden" name="token" value="" />

        @csrf
        @method('PATCH')
    </form>
    <!--end::OTP Form-->

    <!--begin::Recaptcha-->
    <div id="recaptcha-container" class="d-none"></div>
    <!--end::Recaptcha-->

    <script>
        let firebaseConfig = {!! $config  !!}
    </script>
</x-auth-layout>
