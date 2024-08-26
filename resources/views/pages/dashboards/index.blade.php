@section('title', 'Dashboard')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-xl-6">
            <!--begin::Engage widget 9-->
            <div class="card h-lg-100" style="background: linear-gradient(112.14deg, #FF8A00 0%, #FF8A00 100%)">
                <!--begin::Body-->
                <div class="card-body overflow-hidden">
                    <!--begin::Row-->
                    <div class="row align-items-center">
                        <!--begin::Col-->
                        <div class="col-sm-7 pe-0 mb-5 mb-sm-0">
                            <!--begin::Wrapper-->
                            <div class="d-flex justify-content-between h-100 flex-column pt-xl-5 pb-xl-2 ps-xl-7">
                                <!--begin::Container-->
                                <div class="mb-7">
                                    <!--begin::Title-->
                                    <div class="mb-6">
                                        @if( $counters['providers'] > 0 )
                                            <h3 class="fs-2x fw-semibold text-white">You are online...</h3>
                                            <span class="fw-semibold text-white opacity-75">Add more services and keep growing.</span>
                                        @else
                                            <h3 class="fs-2x fw-semibold text-white">You are not online yet !</h3>
                                            <span class="fw-semibold text-white opacity-75">Please create your first provider to start your journey</span>
                                        @endif
                                    </div>
                                    <!--end::Title-->

                                    <!--begin::Items-->
                                    <div class="d-flex align-items-center flex-wrap d-grid gap-2 ">
                                        <!--begin::Item-->
                                        <div class="col-4 d-flex align-items-center me-5 me-xl-13">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px symbol-circle me-3">
                                                <span class="symbol-label" style="background: rgba(255, 255, 255, 0.15);">
                                                    <i class="fas fs-4 fa-magnet text-white"></i>
                                                </span>
                                            </div>
                                            <!--end::Symbol-->

                                            <!--begin::Info-->
                                            <div class="m-0">
                                                <a href="{{ route('acp.providers.index') }}" class="text-white text-opacity-75 fs-8">Providers</a>
                                                <span class="fw-bold text-white fs-7 d-block">{{ $counters['providers'] }}</span>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Item-->

                                        <!--begin::Item-->
                                        <div class="col-4 d-flex align-items-center">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px symbol-circle me-3">
                                                <span class="symbol-label" style="background: rgba(255, 255, 255, 0.15);">
                                                    <i class="fas fs-4 fa-gear text-white"></i>
                                                </span>
                                            </div>
                                            <!--end::Symbol-->

                                            <!--begin::Info-->
                                            <div class="m-0">
                                                <a href="{{ route('acp.services.index') }}" class="text-white text-opacity-75 fs-8">Services</a>
                                                <span class="fw-bold text-white fs-7 d-block">{{ $counters['services'] }}</span>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Items-->
                                    <!--begin::Items-->
                                    <div class="d-flex align-items-center flex-wrap d-grid gap-2 mt-10 ">
                                        <!--begin::Item-->
                                        <div class="col-4 d-flex align-items-center me-5 me-xl-13">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px symbol-circle me-3">
                                                <span class="symbol-label" style="background: rgba(255, 255, 255, 0.15);">
                                                    <i class="fas fs-4 fa-horse-head text-white"></i>
                                                </span>
                                            </div>
                                            <!--end::Symbol-->

                                            <!--begin::Info-->
                                            <div class="m-0">
                                                <a href="{{ route('acp.horses.index') }}" class="text-white text-opacity-75 fs-8">Horses</a>
                                                <span class="fw-bold text-white fs-7 d-block">{{ $counters['horses'] }}</span>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Item-->

                                        <!--begin::Item-->
                                        <div class="col-4 d-flex align-items-center">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px symbol-circle me-3">
                                                <span class="symbol-label" style="background: rgba(255, 255, 255, 0.15);">
                                                    <i class="fas fs-4 fa-person-running text-white"></i>
                                                </span>
                                            </div>
                                            <!--end::Symbol-->

                                            <!--begin::Info-->
                                            <div class="m-0">
                                                <a href="{{ route('acp.trainers.index') }}" class="text-white text-opacity-75 fs-8">Trainers</a>
                                                <span class="fw-bold text-white fs-7 d-block">{{ $counters['trainers'] }}</span>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Items-->
                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--begin::Col-->

                        <!--begin::Col-->
                        <div class="col-sm-5 text-center">
                            <!--begin::Illustration-->
                            <img src="{{ asset('assets/media/illustrations/misc/bucking-horse.svg') }}" class="h-200px h-lg-250px my-n6" alt="">
                            <!--end::Illustration-->
                        </div>
                        <!--begin::Col-->
                    </div>
                    <!--begin::Row-->

                    <!--begin::Action-->
                    <div class="m-0">
                        <a href="{{ route('acp.providers.create') }}" class="btn btn-color-white bg-white bg-opacity-15 bg-hover-opacity-25 fw-semibold">
                            Add provider
                        </a>
                        <a href="{{ route('acp.services.create') }}" class="btn btn-color-white bg-white bg-opacity-15 bg-hover-opacity-25 fw-semibold">
                            Add service
                        </a>
                    </div>
                    <!--begin::Action-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Engage widget 9-->

        </div>
        <!--end::Col-->

        <div class="col-xl-6">
            <!--begin::Engage widget 1-->
            <div class="card h-md-100">
                <!--begin::Body-->
                <div class="row card-body pb-0">
                    <!--begin::Links-->
                    <div class="d-flex flex-column">
                        <!--begin::Title-->
                        <h1 class="fw-semibold text-gray-800 text-center lh-lg">
                            You have <span class="text-primary">{{ $bookings['count'] }}</span> upcoming bookings.
                        </h1>
                        <!--end::Title-->

                        <div class="table-responsive mb-0">
                            <table class="table">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-8">
                                    @if( $bookings['count'] > 0 )
                                        @foreach( $bookings['data'] as $booking )
                                        <tr>
                                            <td>{{ $booking->start_time }}</td>
                                            <td>{{ $booking->user->name }}</td>
                                            <th>{{ $booking->bookable->name }}</th>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-gray-400">You might have more bookings, please review all bookings <a href="{{ route('acp.bookings.index') }}">here</a>.</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-gray-400">No upcoming bookings found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--end::Links-->
                </div>
                <div class="row card-body pt-0">
                    <div class="">
                        <!--begin::Link-->
                        <a class="btn btn-sm btn-primary me-2" href="{{ route('acp.bookings.index') }}">
                            View all bookings
                        </a>
                        <!--end::Link-->
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Engage widget 1-->

        </div>
    </div>
    <!--end::Row-->
</x-default-layout>
