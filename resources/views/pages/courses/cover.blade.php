<div class="card mb-0">
    <div class="card-body d-flex flex-column justify-content-end py-2 p-0">
        <div class="d-flex flex-wrap flex-sm-nowrap">
            <div class="flex-grow-1 align-items-center mt-5">
                <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                    {{ $course->name }}
                </span>
            </div>

            <div class="fs-7 d-flex align-items-center">
                <!--begin::Add class-->
                <a href="{{ route('acp.courses.create_class', ['course_id' => $course->id, 'tab' => 'classes']) }}" class="d-flex align-items-center btn btn-light-primary">
                    <i class="fas fa-solid fa-plus fs-2"></i>
                    <span class="d-none d-md-block">Add Class</span>
                </a>
                <!--end::Add class-->
            </div>
        </div>

        <div class="hover-scroll-x">
            <ul class="flex-nowrap text-nowrap nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == '' || request()->get('tab') == 'overview' ? 'active' : '' }}" href="{{ route('acp.courses.edit', ['course_id' => $course->id, 'tab' => 'overview' ]) }}">
                        Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == 'schedule' ? 'active' : '' }}" href="{{ route('acp.courses.schedule', ['course_id' => $course->id, 'tab' => 'schedule' ]) }}">
                        Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-0 me-10 {{ request()->get('tab') == 'classes' ? 'active' : '' }}" href="{{ route('acp.courses.classes', ['course_id' => $course->id, 'tab' => 'classes' ]) }}">
                        Classes
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
