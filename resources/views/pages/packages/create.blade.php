@section('title', 'Packages')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                @if( isset( $error ) )
                    <div class="mb-7 alert alert-dismissible bg-light-danger d-flex align-items-center p-5">
                        <i class="fas fs-2 text-danger fa-xmark"></i>

                        <!--begin::Wrapper-->
                        <div class="ms-2 d-flex flex-column">
                            <h4 class="mb-1 text-error-emphasis">Error</h4>
                            <span>{{ $error }}</span>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                @else
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.packages.index') }}" id="em_create_package" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.packages.store') }}" enctype="multipart/form-data" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input name="active" class="form-check-input" type="checkbox" value="1" id="active" />
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="0" />
                        <label for="sort" class="form-label">Sort</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="packageable_type" class="form-select form-select-solid" id="packageable_type" data-control="select2" data-close-on-select="true" data-placeholder="Select {{ $package_type }}" data-allow-clear="true">
                            <option></option>
                            @foreach( $package_types as $key => $value  )
                                <option data-redirect-url="{{ route('acp.packages.create', [ 'package_type' => $key ] ) }}" value="{{ $key }}" {{ $key == request()->get('package_type') ? 'selected="selected"' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <label for="packageable_type" class="required form-label">{{ ucwords( $package_type ) }}</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="packageable_id" class="form-select form-select-solid" id="packageable_id" data-control="select2" data-close-on-select="true" data-placeholder="Select {{ $package_type }}" data-allow-clear="true">
                            <option></option>
                            @foreach( $packageables as $packageable )
                                <option value="{{ $packageable->id }}" {{ $packageable->id == request()->get('packageable_id') ? 'selected="selected"' : '' }}>{{ $packageable->name }}</option>
                            @endforeach
                        </select>
                        <label for="packageable_id" class="required form-label">{{ ucwords( $package_type ) }}</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Name" />
                        <label for="name" class="required form-label">Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="price" name="price" class="form-control form-control-solid" placeholder="Price" />
                        <label for="price" class="required form-label">Price</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="quantity" name="quantity" class="form-control form-control-solid" placeholder="Quantity" />
                        <label for="quantity" class="required form-label">Quantity</label>
                    </div>
                    <button type="submit" id="submit_em_create_package" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Package' ])
                    </button>
                    @csrf
                </form>
                <!--end::Form-->
                @endif
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
