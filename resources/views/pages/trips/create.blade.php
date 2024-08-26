@section('title', 'Trips')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.trips.index') }}" id="em_create_trip" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.trips.store') }}" enctype="multipart/form-data" method="post">
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
                                <option value="{{ $provider->id }} {{ $provider->id == request()->get('provider_id') ? 'selected="selected"' : '' }}">{{ $provider->name }}</option>
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
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Trip Name" />
                        <label for="name" class="required form-label">Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea id="description" name="description" class="form-control form-control-solid h-250px" placeholder="Description"></textarea>
                        <label for="description" class="required form-label">Description</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="type" class="form-select form-select-solid" id="type" aria-label="Type" data-control="select2" data-close-on-select="true" data-placeholder="Select type" data-allow-clear="false">
                            <option></option>
                            @foreach( $types as $type => $name )
                                <option value="{{ $type }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="type" class="required form-label">Event Type</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea id="included_items" name="included_items" class="form-control form-control-solid h-250px" placeholder="Included Items"></textarea>
                        <label for="included_items" class="form-label">Included Items</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea id="excluded_items" name="excluded_items" class="form-control form-control-solid h-250px" placeholder="Excluded Items"></textarea>
                        <label for="excluded_items" class="form-label">Excluded Items</label>
                    </div>

                    <!--begin::Dropzone-->
                    <div class="mb-7">
                        <div class="dropzone bg-gray-100 border-0" id="em_dropzone">
                            <!--begin::Message-->
                            <div class="dz-message needsclick">
                                <i class="fas fs-2 fa-solid fa-image"></i>

                                <!--begin::Info-->
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">Trip images</h3>
                                    <span class="fs-7 fw-semibold text-gray-400 dz-error-append">Drag or click to upload upto 10 files.</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                    </div>
                    <!--end::Dropzone-->

                    <div class="form-floating mb-7">
                        <input type="text" id="price" name="price" class="form-control form-control-solid" placeholder="Trip Price" />
                        <label for="price" class="required form-label">Trip Price</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="capacity" name="capacity" class="form-control form-control-solid" placeholder="Trip Capacity" value="1" />
                        <label for="capacity" class="required form-label">Trip Capacity</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="origin_country_id" class="form-select form-select-solid" id="origin_country_id" aria-label="Origin" data-control="select2" data-close-on-select="true" data-placeholder="Select origin">
                            @foreach( $countries as $country )
                                <option {{ $country->code == 'AE' ? ' selected="selected"' : ''}} value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <label for="origin_country_id" class="required form-label">Origin</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="destination_country_id" class="form-select form-select-solid" id="destination_country_id" aria-label="Destination" data-control="select2" data-close-on-select="true" data-placeholder="Select destination" data-allow-clear="true">
                            <option></option>
                            @foreach( $countries as $country )
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <label for="destination_country_id" class="required form-label">Destination</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="dates" name="dates" class="form-control form-control-solid" placeholder="Trip Dates" />
                        <label for="dates" class="required form-label">Trip Dates</label>
                    </div>
                    <button type="submit" id="submit_em_create_trip" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Event' ])
                    </button>

                    <input type="hidden" id="start_date" name="start_date" value="{{ now()->add('3 days')->format('Y-m-d') }}" />
                    <input type="hidden" id="end_date" name="end_date" value="{{ now()->add('10 days')->format('Y-m-d') }}" />
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
