@section('title', 'Categories')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.categories.index') }}" id="em_create_category" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.categories.store') }}" data-em-redirect="{{ route('acp.categories.index') }}" method="post">
                    <div class="form-floating mb-7">
                        <input type="text" id="em_category_name" name="name" class="form-control form-control-solid" placeholder="Name" />
                        <label for="name" class="required form-label">Category Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="number" id="em_category_sort" name="sort" class="form-control form-control-solid" placeholder="Sort" />
                        <label for="sort" class="required form-label">Sorting Order</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="file" id="em_category_icon" name="icon" class="form-control form-control-solid" placeholder="Icon" />
                        <label for="icon" class="form-label">Icon</label>
                    </div>

                    <button type="submit" id="submit_em_create_category" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Category' ])
                    </button>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
