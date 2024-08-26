@section('title', 'Services')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        @include('pages.courses.cover')

        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.courses.index') }}" id="em_create_schedule" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.courses.store_schedule', ['course_id' => $course->id ] ) }}" method="post">
                    @foreach( $days as $day => $title )
                        @php
                            $inputName          = "schedule[{$day}]";
                            $inputClass         = "schedule-{$day}";
                            $isScheduled        = false;
                            if( isset( $schedules[$day] ) ){
                                $isScheduled    = true;
                            }
                        @endphp
                        <div class="row day-container">
                            <div class="d-flex justify-content-between">
                                <div class="day-active-container mb-7 form-check form-switch form-check-custom form-check-solid">
                                    <input {!! $isScheduled ? 'checked="checked"' : '' !!} name="active[active]" class="day-active form-check-input" type="checkbox" value="1" id="{{ $inputName }}[active]"/>
                                    <label for="{{ $inputName }}[active]" class="form-check-label">{{ $title }}</label>
                                </div>
                                <!--begin::Form group-->
                                <div class="mb-7">
                                    <a data-repeater-create href="javascript:;" class="day-hours-repeat btn btn-flex btn-light-primary {!! $isScheduled ? '' : 'd-none' !!}">
                                        <i class="fas fa-solid fa-plus fs-2"></i>
                                        {{ __('acp/general.layout.add_slot') }}
                                    </a>
                                </div>
                            </div>
                            <!--end::Form group-->

                            @if( $isScheduled )
                                @foreach( $schedules[$day] as $key => $schedule )
                                    @php
                                        $savedInputName = "scheduled[{$day}][{$key}]";
                                        $savedInputClass= "scheduled-{$day}-{$key}";
                                    @endphp
                                    <div class="day-hours-container">
                                        <div class="d-flex justify-content-start gap-3 flex-md-row flex-column">
                                            <div class="mb-7 form-floating">
                                                <input type="text" id="{{ $savedInputName }}[start_time]" name="{{ $savedInputName }}[start_time]" class="hours-selection form-control form-control-solid" placeholder="Starting Hour" value="{{ old( 0, $isScheduled ? $schedule->start_time : null ) }}" />
                                                <label for="{{ $savedInputName }}[start_time]" class="{{ $savedInputClass }}-start_time required form-label">Starting Hour</label>
                                            </div>
                                            <div class="mb-7 form-floating">
                                                <input type="text" id="{{ $savedInputName }}[end_time]" name="{{ $savedInputName }}[end_time]" class="hours-selection form-control form-control-solid" placeholder="Ending Hour" value="{{ old( 0, $isScheduled ? $schedule->end_time : null ) }}" />
                                                <label for="{{ $savedInputName }}[end_time]" class="{{ $savedInputClass }}-end_time required form-label">Ending Hour</label>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between gap-3 mb-7">
                                                <div class="form-floating">
                                                    <input type="text" id="{{ $savedInputName }}[price]" name="{{ $savedInputName }}[price]" class="price-selection form-control form-control-solid" placeholder="Price" value="{{ old( 0, $isScheduled ? $schedule->price : null ) }}" />
                                                    <label for="{{ $savedInputName }}[price]" class="{{ $savedInputClass }}-price form-label">Markup Price</label>
                                                </div>
                                                <a href="javascript:;" data-input-name="{{ $savedInputName }}" class="day-delete btn btn-light-danger p-4">
                                                    <i class="fas fa-solid fa-trash-can fs-4"></i>
                                                    {{ __('acp/general.layout.delete') }}
                                                </a>
                                            </div>

                                            <input type="hidden" class="day-active-toggle" name="{{ $savedInputName }}[active]" value="1" />
                                            <input type="hidden" name="{{ $savedInputName }}[id]" value="{{ $schedule->id }}" />
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div class="day-hours-container d-none" data-repeater-list="{{ $inputName }}">
                                <div class="d-flex justify-content-start gap-3 flex-md-row flex-column" data-repeater-item>
                                    <div class="mb-7 form-floating">
                                        <input type="text" name="{{ $inputName }}[start_time]" class="hours-selection form-control form-control-solid" placeholder="Starting Hour" value="" />
                                        <label class="required form-label">Starting Hour</label>
                                    </div>
                                    <div class="mb-7 form-floating">
                                        <input type="text" name="{{ $inputName }}[end_time]" class="hours-selection form-control form-control-solid" placeholder="Ending Hour" value="" />
                                        <label class="required form-label">Ending Hour</label>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between gap-3 mb-7">
                                        <div class="form-floating">
                                            <input type="text" name="{{ $inputName }}[price]" class="price-selection form-control form-control-solid" placeholder="Price" value="" />
                                            <label class="form-label">Markup Price</label>
                                        </div>
                                        <a data-repeater-delete href="javascript:" class="btn btn-light-danger p-4">
                                            <i class="fas fa-solid fa-trash-can fs-4"></i>
                                            {{ __('acp/general.layout.delete') }}
                                        </a>
                                    </div>

                                    <input type="hidden" class="day-active-toggle" name="{{ $inputName }}[active]" value="1" />
                                </div>
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
