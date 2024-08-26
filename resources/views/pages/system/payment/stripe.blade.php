<x-system-layout>
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-center flex-column-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center text-center p-10">
                <!--begin::Illustration-->
                <div class="mb-3">
                    <img src="{{ image('logos/logo-black.png') }}" class="mw-100 mh-100px theme-light-show" alt=""/>
                    <img src="{{ image('logos/logo-white.png') }}" class="mw-100 mh-100px theme-dark-show" alt=""/>
                </div>
                <!--end::Illustration-->

                <!--begin::Title-->
                <h3 class="fs-4 fw-bold text-gray-600 mb-4">
                    {{ __('api/transaction.title_wait') }}
                </h3>
                <!--end::Title-->

                <!--begin::Text-->
                <div class="fw-semibold fs-7 text-gray-500 mb-7">
                    {{ __('api/transaction.text_wait') }}
                </div>
                <!--end::Text-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Root-->

    @if( isset( $response ) )
        @push('scripts')
            <script>
                let response = '{!! $response !!}';
                window.addEventListener( 'flutterInAppWebViewPlatformReady', function() {
                    window.flutter_inappwebview.callHandler( 'response', JSON.stringify( response ) );
                })

                window.postMessage( JSON.stringify( response ) );
            </script>
        @endpush
    @endif
</x-system-layout>
