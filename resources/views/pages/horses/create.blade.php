@section('title', 'Horses')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.horses.index') }}" id="em_create_horse" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.horses.store') }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input checked="checked" name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="provider_id" class="form-select form-select-solid" id="em_horse_provider_id" aria-label="Provider" data-control="select2" data-close-on-select="true" data-placeholder="Select provider" data-allow-clear="true">
                            <option></option>
                            @foreach( $providers as $provider )
                                <option value="{{ $provider->id }}" {{ $provider->id == request()->get('provider_id') ? 'selected="selected"' : '' }}>{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        <label for="provider_id" class="required form-label">Provider</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Horse Name" />
                        <label for="name" class="required form-label">Horse Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="file" id="em_category_image" name="image" class="form-control form-control-solid" placeholder="Image" />
                        <label for="image" class="form-label">Image</label>
                    </div>
                    <div class="form mb-7">
                        <label for="gender" class="required form-label">Gender</label>
                        @foreach( $genders as $gender => $title )
                            <div class="form-check form-check-solid mb-5">
                                <input class="form-check-input" type="radio" value="{{ $gender }}" id="em_horse_gender_{{ $gender }}" name="gender">
                                <label for="em_horse_gender_{{ $gender }}" class="form-check-label">{{ $title }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-floating mb-7">
                        <select name="level" class="form-select form-select-solid" id="level" aria-label="Level" data-control="select2" data-close-on-select="true" data-placeholder="Select level" data-allow-clear="true">
                            <option></option>
                            @foreach( $levels as $level => $title )
                                <option value="{{ $level }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        <label for="level" class="required form-label">Level</label>
                    </div>

                    <button type="submit" id="submit_em_create_horse" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Horse' ])
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
