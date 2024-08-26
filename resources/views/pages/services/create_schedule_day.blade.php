@section('title', 'Services')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.services.index') }}" id="em_create_schedule" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.services.store_schedule_day', ['service_id' => $service->id ] ) }}" method="post">
                    @foreach( $days as $day => $title )
                        @php
                            $inputName          = "schedule[{$day}]";
                            $inputClass         = "schedule-{$day}";
                            $isScheduled        = false;
                            if( isset( $schedules[$day] ) ){
                                $isScheduled    = true;
                            }
                        @endphp
                        <div class="row day-container min-vw-300px">
                            <div class="day-active-container mb-7 form-check form-switch form-check-custom form-check-solid">
                                <input {!! $isScheduled ? 'checked="checked"' : '' !!} name="{{ $inputName }}[active]" class="day-active form-check-input" type="checkbox" value="1" id="{{ $inputName }}[active]"/>
                                <label for="{{ $inputName }}[active]" class="form-check-label">{{ $title }}</label>
                            </div>
                            <div class="day-hours-container d-flex justify-content-start gap-3 flex-md-row flex-column {!! $isScheduled ? '' : 'd-none' !!}">
                                <div class="mb-7 form-floating">
                                    <input type="text" id="{{ $inputName }}[start_time]" name="{{ $inputName }}[start_time]" class="hours-selection form-control form-control-solid" placeholder="Starting Hour" value="{{ old( 0, $isScheduled ? $schedules[$day]->start_time : null ) }}" />
                                    <label for="{{ $inputName }}[start_time]" class="{{ $inputClass }}-start_time required form-label">Starting Hour</label>
                                </div>
                                <div class="mb-7 form-floating">
                                    <input type="text" id="{{ $inputName }}[end_time]" name="{{ $inputName }}[end_time]" class="hours-selection form-control form-control-solid" placeholder="Ending Hour" value="{{ old( 0, $isScheduled ? $schedules[$day]->end_time : null ) }}" />
                                    <label for="{{ $inputName }}[end_time]" class="{{ $inputClass }}-end_time required form-label">Ending Hour</label>
                                </div>
                                <div class="mb-7 form-floating">
                                    <input type="text" id="{{ $inputName }}[price]" name="{{ $inputName }}[price]" class="price-selection form-control form-control-solid" placeholder="Price" value="{{ old( 0, $isScheduled ? $schedules[$day]->price : null ) }}" />
                                    <label for="{{ $inputName }}[price]" class="{{ $inputClass }}-price form-label">Markup Price</label>
                                </div>

                                <input type="hidden" name="{{ $inputName }}[day]" value="{{ $inputName }}" />

                                @if( $isScheduled )
                                    <input type="hidden" name="{{ $inputName }}[id]" value="{{ $schedules[$day]->id }}" />
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" id="submit_em_create_schedule" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Save Schedule' ])
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
