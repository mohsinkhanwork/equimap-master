@section('title', '')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.providers.index') }}" id="em_create_provider" class="form fv-plugins-bootstrap5 fv-plugins-framework" enctype="multipart/form-data" action="{{ route('acp.providers.store') }}" method="post">
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Name" />
                        <label for="name" class="required form-label">Provider Name</label>
                    </div>
                    @can('edit provider featured')
                        <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                            <input name="featured" class="form-check-input" type="checkbox" value="1" id="featured" />
                            <label class="form-check-label" for="featured">Featured ?</label>
                        </div>
                        <div class="form-floating mb-7 d-none">
                            <input type="text" id="featured_ranking" name="featured_ranking" class="form-control form-control-solid" placeholder="Featured Ranking" value="0" />
                            <label for="featured_ranking" class="form-label">Featured Ranking ?</label>
                        </div>
                    @endcan
                    <div class="form-floating mb-7">
                        <textarea data-kt-autosize="true" id="em_provider_address" name="address" rows="3" class="form-control form-control-solid" placeholder="Address"></textarea>
                        <label for="em_provider_address" class="required form-label">Address</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea data-kt-autosize="true" id="em_provider_description" name="description" rows="3" class="form-control form-control-solid" placeholder="Address"></textarea>
                        <label for="em_provider_description" class="required form-label">Description</label>
                    </div>

                    <div class="mb-7">
                        <select name="facilities[]" class="form-select form-select-solid required" data-control="select2" data-close-on-select="false" data-placeholder="Select available facilities" data-allow-clear="true" multiple="multiple">
                            <option></option>
                            @foreach( $facilities as $facility )
                                <option value="{{ $facility->id  }}">{{ $facility->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-floating mb-7">
                        <input type="file" id="em_provider_cover" name="cover" class="form-control form-control-solid" placeholder="Icon" />
                        <label for="icon" class="required form-label">Cover Image</label>
                    </div>

                    <!--begin::Dropzone-->
                    <div class="mb-7">
                        <div class="dropzone bg-gray-100 border-0" id="em_dropzone">
                            <!--begin::Message-->
                            <div class="dz-message needsclick">
                                <i class="fas fs-2 fa-solid fa-image"></i>

                                <!--begin::Info-->
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">Gallery images</h3>
                                    <span class="fs-7 fw-semibold text-gray-400 dz-error-append">Drag or click to upload upto 10 files.</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                    </div>
                    <!--end::Dropzone-->

                    <div class="form-floating mb-7">
                        <input type="text" id="geo_loc_search" name="geo_loc_search" class="form-control form-control-solid" placeholder="Location" />
                        <label for="geo_loc_search" id="geo_loc_search" class="required form-label">Location</label>
                    </div>
                    <div class="d-flex h-250px mb-7 z-index-2000" id="map" data-location="25.0760092,55.24859"></div>

                    <button type="submit" id="submit_em_create_provider" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Provider' ])
                    </button>

                    <input type="hidden" id="geo_loc" name="geo_loc" value="" />
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
