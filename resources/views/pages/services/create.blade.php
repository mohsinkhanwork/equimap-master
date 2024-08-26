@section('title', 'Services')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.services.index') }}" id="em_create_service" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.services.store') }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input name="active" class="form-check-input" type="checkbox" value="1" id="active" />
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="0" />
                        <label for="sort" class="form-label">Sort</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="provider_id" class="form-select form-select-solid" id="provider_id" aria-label="Provider" data-control="select2" data-close-on-select="true" data-placeholder="Select provider" data-allow-clear="true">
                            <option></option>
                            @foreach( $providers as $provider )
                                <option value="{{ $provider->id }}" {{ $provider->id == request()->get('provider_id') ? 'selected="selected"' : '' }}>{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        <label for="provider_id" class="required form-label">Provider</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="category_id" class="form-select form-select-solid" id="category_id" aria-label="Category" data-control="select2" data-close-on-select="true" data-placeholder="Select category" data-allow-clear="true">
                            <option></option>
                            @foreach( $categories as $category )
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <label for="category_id" class="required form-label">Category</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Service Name" />
                        <label for="name" class="required form-label">Service Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea data-kt-autosize="true" id="description" name="description" rows="3" class="form-control form-control-solid" placeholder="Address"></textarea>
                        <label for="description" class="required form-label">Description</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="price" name="price" class="form-control form-control-solid" placeholder="Service Price" />
                        <label for="price" class="required form-label">Service Price</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="unit" class="form-select form-select-solid" id="unit" aria-label="Scheduling Unit" data-control="select2" data-close-on-select="true" data-placeholder="Select level" data-allow-clear="true">
                            <option></option>
                            @foreach( $units as $unit => $title )
                                <option value="{{ $unit }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        <label for="unit" class="required form-label">Scheduling Unit</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="capacity" name="capacity" class="form-control form-control-solid" placeholder="Service Capacity" value="1" />
                        <label for="capacity" class="required form-label">Service Capacity</label>
                    </div>

                    <button type="submit" id="submit_em_create_service" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Service' ])
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
