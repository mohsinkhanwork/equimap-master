@section('title', 'Banners')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.banners.index') }}" id="em_create_banner" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.banners.update', [ 'banner_id' => $banner->id ]) }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input checked="checked" name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="{{ old('sort', $banner->sort ) }}" />
                        <label for="sort" class="form-label">Sort</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Name" value="{{ old('name', $banner->name ) }}" />
                        <label for="name" class="required form-label">Banner Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="type" id="type" class="form-select form-select-solid" aria-label="Type" data-control="select2" data-close-on-select="true" data-placeholder="Select banner type" data-allow-clear="true">
                            <option></option>
                            @foreach( $types as $type => $name )
                                <option {!! $type == $banner->type ? 'selected="selected"' : ''  !!} value="{{ $type }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="type" class="required form-label">Navigation Type</label>
                    </div>

                    <div class="form-floating mb-7 link-type {!! $banner->type != 'app' ? 'd-none' : ''  !!}" id="link-app-container">
                        <select name="link_app" class="form-select form-select-solid" id="link_app" aria-label="Entity" data-control="select2" data-close-on-select="true" data-placeholder="Select linked entity" data-allow-clear="true">
                            <option></option>
                            @foreach( $entities as $entity => $name )
                                <option {!! $entity == $banner->link ? 'selected="selected"' : ''  !!} value="{{ $entity }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="link_app" class="required form-label">Application Link</label>
                    </div>

                    <div class="form-floating mb-7 link-type {!! $banner->type != 'web' ? 'd-none' : ''  !!}" id="link-web-container">
                        <input type="text" id="link_web" name="link_web" class="form-control form-control-solid" placeholder="Website Link" value="{{ $banner->type == 'web' ? old('link', $banner->link ) : '' }}" />
                        <label for="link_web" class="required form-label">Website Link</label>
                    </div>

                    <div class="form-floating mb-7">
                        <input type="text" id="params" name="params" class="px-2 form-control form-control-solid" placeholder="Comma separated" value="{{ old('params', $banner->params ) }}" />
                        <label for="params" class="form-label">Link Parameters</label>
                    </div>

                    <div class="d-flex flex-row card-rounded bg-light mb-7">
                        @if( isset( $banner->image ) )
                            <div class="d-flex symbol m-5 bg-white p-3">
                                <img src="{{ storage_url( $banner->image->path ) }}" class="w-100px h-auto" alt="" />
                            </div>
                        @endif
                        <div class="d-flex form-floating">
                            <input type="file" id="image" name="image" class="form-control form-control-solid" placeholder="Banner" />
                            <label for="image" class="form-label">Banner Image</label>
                        </div>
                    </div>

                    <button type="submit" id="submit_em_create_banner" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update Banner' ])
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
