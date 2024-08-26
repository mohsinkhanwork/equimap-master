@section('title', '')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.trainers.index') }}" id="em_create_trainer" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.trainers.update', [ 'trainer_id' => $trainer->id ]) }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input {!! $trainer->active == 1 ? 'checked="checked"' : '' !!} name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="provider_id" class="form-select form-select-solid" id="em_trainer_provider_id" aria-label="Provider" data-control="select2" data-close-on-select="true" data-placeholder="Select provider" data-allow-clear="true">
                            <option></option>
                            @foreach( $providers as $provider )
                                <option {!! $provider->id == $trainer->provider_id ? 'selected="selected"' : ''  !!} value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        <label for="em_trainer_provider_id" class="required form-label">Provider</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="em_trainer_name" name="name" class="form-control form-control-solid" placeholder="Trainer Name" value="{{ old('name', $trainer->name ) }}" />
                        <label for="em_trainer_name" class="required form-label">Trainer Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="number" id="em_trainer_phone" name="phone" class="form-control form-control-solid" placeholder="Phone Number" value="{{ old('phone', $trainer->phone ) }}" />
                        <label for="em_trainer_phone" class="required form-label">Phone Number</label>
                    </div>

                    <button type="submit" id="submit_em_create_trainer" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update Trainer' ])
                    </button>

                    @csrf
                    @method('PATCH')
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
