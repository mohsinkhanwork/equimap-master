@extends('layout.master')

@section('content')
    <!--begin::App-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Page bg image-->
        <style>
            body {
                background-image: url({{ image('auth/bg10.jpeg') }});
            }

            [data-bs-theme="dark"] body {
                background-image: url({{ image('auth/bg10-dark.jpeg') }});
            }
        </style>
        <!--end::Page bg image-->

        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-none d-lg-flex flex-lg-row-fluid">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <!--begin::Title-->
                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">

                    </h1>
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="bg-body p-10 rounded-4 text-gray-600 fs-base text-justify fw-semibold w-400px">
                        @php
                            $total  = count( __('acp/quotes') )-1;
                            $random = rand( 0,$total )
                        @endphp
                        <p>{{ __('acp/quotes.'.$random.'.quote') }}</p>
                        <p class="text-primary mb-0">-- {{ __('acp/quotes.'.$random.'.author') }}</p>
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Content-->
            </div>
            <!--begin::Aside-->

            <!--begin::Body-->
            <div class="d-flex flex-column-fluid justify-content-center justify-content-lg-end p-12">
                <!--begin::Wrapper-->
                <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                    <!--begin::Content-->
                    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">

                            <!--begin::Form-->
                            {{ $slot }}
                            <!--end::Form-->

                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Footer-->
                        <div class=" d-flex flex-center">
                            @include('partials._auth_lang_switcher')

                            <!--begin::Links-->
                            <div class="d-flex fw-semibold text-secondary fs-base gap-5">
                                <a href="{{ route('pages.show', [ 'page_slug' => 'terms' ] ) }}" target="_blank">{{ __('acp/general.terms') }}</a> -
                                <a href="{{ route('pages.show', [ 'page_slug' => 'privacy' ]) }}" target="_blank">{{ __('acp/general.privacy') }}</a> -
                                <a href="{{ route('pages.show', [ 'page_slug' => 'contact' ]) }}" target="_blank">{{ __('acp/general.contact') }}</a>
                            </div>
                            <!--end::Links-->
                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--end::App-->

@endsection
