@section('title', 'Courses')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        @include('pages.courses.cover')

        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.courses.index') }}" id="em_create_class" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.courses.store_class', ['course_id' => $course->id ] ) }}" method="post">
                    <div class="form-floating mb-7">
                        <input type="text" id="sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="0" />
                        <label for="sort" class="form-label">Sort</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Trip Name" />
                        <label for="name" class="required form-label">Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea id="description" name="description" class="form-control form-control-solid h-250px" placeholder="Description"></textarea>
                        <label for="description" class="required form-label">Description</label>
                    </div>

                    <button type="submit" id="submit_em_create_class" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Class' ])
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
