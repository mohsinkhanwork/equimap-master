@section('title', 'Banners')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.banners.index') }}" id="em_create_banner" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.banners.store') }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input checked="checked" name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="0" />
                        <label for="sort" class="form-label">Sort</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Name" />
                        <label for="name" class="required form-label">Banner Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="type" id="type" class="form-select form-select-solid" aria-label="Type" data-control="select2" data-close-on-select="true" data-placeholder="Select banner type" data-allow-clear="true">
                            <option></option>
                            @foreach( $types as $type => $name )
                                <option value="{{ $type }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="type" class="required form-label">Navigation Type</label>
                    </div>

                    <div class="form-floating mb-7 d-none link-type" id="link-app-container">
                        <select name="link_app" class="form-select form-select-solid" id="link_app" aria-label="Entity" data-control="select2" data-close-on-select="true" data-placeholder="Select linked entity" data-allow-clear="true">
                            <option></option>
                            @foreach( $entities as $entity => $name )
                                <option value="{{ $entity }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="link_app" class="required form-label">Application Link</label>
                    </div>

                    <div class="form-floating mb-7 d-none link-type" id="link-web-container">
                        <input type="text" id="link_web" name="link_web" class="form-control form-control-solid" placeholder="Website Link" />
                        <label for="link_web" class="required form-label">Website Link</label>
                    </div>

                    <div class="form-floating mb-7">
                        <input type="text" id="params" name="params" class="px-2 form-control form-control-solid" placeholder="Comma separated" />
                        <label for="params" class="form-label">Link Parameters</label>
                    </div>

                    <div class="form-floating mb-7">
                        <input type="file" id="image" name="image" class="form-control form-control-solid" placeholder="Image" />
                        <label for="image" class="required form-label">Banner Image</label>
                    </div>

                    <button type="submit" id="submit_em_create_banner" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Banner' ])
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
