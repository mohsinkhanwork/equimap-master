<x-system-layout>
    <style>
        body { background-image: url('{{ image('auth/bg10.jpeg') }}') }
        [data-bs-theme="dark"] body { background-image: url('{{ image('auth/bg10-dark.jpeg') }}') }

        .logo svg {
            fill: #ff0000;
        }
    </style>

    <!--begin::Title-->
    <h1 class="fw-bold fs-2qx mb-4 text-primary text-uppercase text-center">
        <img src="{{ image('logos/logo-min.svg') }}" class="logo w-50px" alt="" />
        <img src="{{ image('logos/logo-text.svg') }}" class="logo w-100px" alt="" />
    </h1>
    <!--end::Title-->

    <!--begin::Text-->
    <div class="page-content align-content-start fs-7">
        <!--begin::Row-->
        <div class="row g-5 g-xl-10">
            <!--begin::Card widget-->
            <div class="card card-flush">
                <!--begin::Card body-->
                <div class="card-body d-flex flex-column p-5">
                @if( session( 'error' ) )
                    <!--begin::Alert-->
                        <div class="m0 alert alert-dismissible alert-danger d-flex flex-row text-dark">
                            <!--begin::Icon-->
                            <i class="fas fa-solid fs-2 fa-circle-exclamation me-5 text-danger-emphasis"></i>
                            <!--end::Icon-->

                            <!--begin::Wrapper-->
                                <!--begin::Content-->
                                <span class="text-danger-emphasis">{{ session( 'error' ) }}</span>
                                <!--end::Content-->
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Alert-->
                @endif
                    <!--begin::Form-->
                    <form autocomplete="off" data-redirect-url="{{ route('web.booking.checkin') }}" id="em_checkin_booking" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('web.booking.checkin') }}" method="get">
                        <div class="form-floating mb-7">
                            <input type="text" id="reference" name="reference" class="form-control form-control-solid" placeholder="Reference" value="{{ old('reference') }}" />
                            <label for="reference" class="form-label">Booking Reference</label>
                        </div>

                        <button type="submit" id="submit_em_checkin_booking" class="btn btn-primary">
                            @include('partials.general._button-indicator', ['label' => 'Check-In' ])
                        </button>
                        @csrf
                    </form>
                    <!--end::Form-->

                    <p class="m-0 mt-5">Current Time: {{ date('d-m-Y H:i:s') }}</p>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card widget-->
        </div>
    <!--end::Text-->
    </div>
</x-system-layout>
