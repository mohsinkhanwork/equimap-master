@section('title', 'Classes')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        @include('pages.courses.cover')

        <!--begin::Card widget-->
        <div class="card card-flush mt-5 h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-5">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th>ID</th>
                            <th>Sort</th>
                            <th>Course</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach( $course->classes as $class )
                                <tr>
                                    <td>{{ $class->id }}</td>
                                    <td>{{ $class->sort }}</td>
                                    <td>{{ $class->course->name }}</td>
                                    <td>{{ $class->name }}</td>
                                    <td>
                                        @can('edit course')
                                            <a href="{{ route('acp.courses.edit_class', [ 'course_id' => $course->id, 'class_id' => $class->id ]) }}">
                                                <i class="fas fa-solid fa-pen-to-square fs-4"></i>
                                                {{ __('acp/general.layout.edit') }}
                                            </a>
                                        @endcan
                                        @can('delete course')
                                            <a class="em_delete_class" href="{{ route('acp.courses.destroy_class', [ 'course_id' => $course->id, 'class_id' => $class->id ]) }}">
                                                <i class="fas fa-solid fa-trash fs-4"></i>
                                                {{ __('acp/general.layout.delete') }}
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
