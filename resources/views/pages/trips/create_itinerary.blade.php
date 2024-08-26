@section('title', 'Trips')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.trips.index') }}" id="em_create_itinerary" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.trips.store_itinerary', ['trip_id' => $trip->id ] ) }}" method="post">
                    @foreach( $trip->dates as $date )
                        @php
                            $date               = $date->format('Y-m-d');
                            $inputName          = "trip[{$date}]";
                            $inputClass         = "trip-{$date}";
                            $isPlanned          = false;
                            if( isset( $itinerary[$date] ) ){
                                $isPlanned      = true;
                            }
                        @endphp

                        <div class="form-floating mb-7">
                            <textarea id="{{ $inputName }}[description]" name="{{ $inputName }}[description]" class="form-control form-control-solid h-250px" placeholder="Description">{{ old( $inputName."[description]", $isPlanned ? $itinerary[$date]->description : "" ) }}</textarea>
                            <label for="{{ $inputName }}[description]" class="{{ $inputClass }}-description required form-label">{{ $date }}</label>
                        </div>

                        @if( $isPlanned )
                            <input type="hidden" name="{{ $inputName }}[id]" value="{{ $itinerary[$date]->id }}" />
                        @endif
                    @endforeach

                    <button type="submit" id="submit_em_create_itinerary" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Itinerary' ])
                    </button>
                    @csrf
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
