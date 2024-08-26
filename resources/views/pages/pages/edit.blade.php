@section('title', 'Pages')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.pages.index') }}" id="em_create_page" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.pages.update', [ 'page_id' => $page->id ]) }}" method="post">
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input {!! $page->active == 1 ? 'checked="checked"' : '' !!} name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>

                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Name" value="{{ old('name', $page->name ) }}" />
                        <label for="name" class="required form-label">Page Name</label>
                    </div>

                    <div class="form-floating mb-7">
                        <input type="text" id="slug" name="slug" class="form-control form-control-solid" placeholder="Slug" value="{{ old('slug', $page->slug ) }}" />
                        <label for="slug" class="required form-label">Page Slug</label>
                    </div>

                    <div class="form mb-7">
                        <label for="content" class="required form-label">Content</label>
                        <textarea id="content" name="content" class="form-control form-control-solid h-250px">
                            {!! old('content', $page->content )  !!}
                        </textarea>
                    </div>

                    <button type="submit" id="submit_em_create_page" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update Page' ])
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
