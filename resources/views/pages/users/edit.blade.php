@section('title', '')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.users.index') }}" id="em_create_user" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.users.update', ['user_id' => $user->id ]) }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input {!! $user->active == 1 ? 'checked="checked"' : '' !!} name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    @can('verify user')
                        <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                            <input {!! $user->login_verified == 1 ? 'checked="checked"' : '' !!} name="login_verified" class="form-check-input" type="checkbox" value="1" id="login_verified"/>
                            <label class="form-check-label" for="active">Verified</label>
                        </div>
                    @endcan
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Full Name" value="{{ old('name', $user->name ) }}" />
                        <label for="em_trainer_name" class="required form-label">Full Name</label>
                    </div>
                    <div class="mb-7">
                        <select disabled name="country" class="form-select form-select-solid required" data-control="select2" data-close-on-select="true" data-placeholder="Select country" data-allow-clear="false">
                            @foreach( $countries as $country )
                                <option {{ $country->code == $user->phone->getCountry() ? 'selected="selected"' : '' }} value="{{ $country->code }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-floating mb-7 d-flex flex-row">
                        <input disabled type="text" class="rounded-0 rounded-start-2 form-control form-control-solid w-80px" name="dialing_code" id="dialing_code" value="+{{ $user->country->dialing_code }}" />
                        <input disabled type="text" id="login" name="login" class="rounded-0 rounded-end-2 form-control form-control-solid" placeholder="Phone Number" value="{{ old('login', $user->phone->formatForMobileDialingInCountry( $user->phone->getCountry() ) ) }}" />
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
                                <option {{ $user->roles->isNotEmpty() && in_array( $role->name, $user->roles->pluck('name')->toArray() ) ? 'selected="selected"' : '' }} value="{{ $role->name }}">{{ ucfirst( $role->name ) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endcan

                    <button type="submit" id="submit_em_create_user" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update User' ])
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
