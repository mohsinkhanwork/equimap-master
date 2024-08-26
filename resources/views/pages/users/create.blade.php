@section('title', '')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.users.index') }}" id="em_create_user" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.users.store') }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input checked="checked" name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    @can('verify user')
                        <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                            <input name="login_verified" class="form-check-input" type="checkbox" value="1" id="login_verified"/>
                            <label class="form-check-label" for="active">Verified</label>
                        </div>
                    @endcan
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Full Name" />
                        <label for="em_trainer_name" class="required form-label">Full Name</label>
                    </div>
                    <div class="mb-7">
                        <select name="country" id="country" class="form-select form-select-solid required" data-control="select2" data-close-on-select="true" data-placeholder="Select country" data-allow-clear="false">
                            @foreach( $countries as $country )
                                <option {{ $country->code == 'AE' ? 'selected="selected"' : '' }} data-dialing-code="{{ $country->dialing_code }}" value="{{ $country->code }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-floating mb-7 d-flex flex-row">
                        <input disabled type="text" class="rounded-0 rounded-start-2 form-control form-control-solid w-80px" name="dialing_code" id="dialing_code" value="+971" />
                        <input type="text" id="login" name="login" class="rounded-0 rounded-end-2 form-control form-control-solid" placeholder="Phone Number" />
                        <label for="login" class="required form-label">Phone Number</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="password" id="password" name="password" class="form-control form-control-solid" placeholder="Password" />
                        <label for="password" class="required form-label">Password</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-solid" placeholder="Password" />
                        <label for="password_confirmation" class="required form-label">Confirm Password</label>
                    </div>
                    @can('assign user role')
                    <div class="mb-7">
                        <select name="roles[]" class="form-select form-select-solid" data-control="select2" data-close-on-select="true" data-placeholder="Select user roles" data-allow-clear="true" multiple="multiple">
                            <option></option>
                            @foreach( $roles as $role )
                                <option value="{{ $role->name }}">{{ ucfirst( $role->name ) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endcan

                    <button type="submit" id="submit_em_create_user" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add User' ])
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
