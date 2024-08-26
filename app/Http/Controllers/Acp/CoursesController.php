<?php

namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\CourseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseClassDeleteRequest;
use App\Http\Requests\Course\CourseClassStoreRequest;
use App\Http\Requests\Course\CourseClassUpdateRequest;
use App\Http\Requests\Course\CourseScheduleStoreRequest;
use App\Http\Requests\Course\CourseStoreRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\CoursesClass;
use App\Models\Provider;
use App\Models\Schedule;

class CoursesController extends Controller{
    public function index( CourseDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Course::class );
        addJavascriptFile('assets/js/pages/course.listing.js');

        return $dataTable->render('pages.courses.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Course::class );
        addJavascriptFile('assets/js/pages/course.create.js');

        return view('pages.courses.create', [
            'providers'         => Provider::all(),
            'categories'        => Category::all(),
            'unit_types'        => Course::getUnitTypes(),
            'progression_types' => Course::getProgressionTypes(),
        ]);
    }

    public function store( CourseStoreRequest $request, Course $course ){
        ValidatePermission::authorizeWeb( Course::class, 'create' );

        $store      = $course->create( $request->validated() );
        return $store->id > 0
            ? $this->success( 'api/course.create.success', $store )
            : $this->error( 'api/course.create.failed' );
    }

    public function edit( Course $courses, $course_id ){
        $course     = $courses->find( $course_id );
        ValidatePermission::authorizeWeb( $course );
        addJavascriptFile('assets/js/pages/course.create.js');

        return view('pages.courses.edit', [
            'course'            => $course,
            'providers'         => Provider::all(),
            'categories'        => Category::all(),
            'unit_types'        => Course::getUnitTypes(),
            'progression_types' => Course::getProgressionTypes(),
        ]);
    }

    public function update( CourseUpdateRequest $request, Course $courses, $course_id ){
        $course     = $courses->find( $course_id );
        ValidatePermission::authorizeWeb( $course, 'edit' );

        $updated    = $course->update( $request->validated() );
        return $updated
            ? $this->success( 'api/course.update.success' )
            : $this->error( 'api/course.update.failed' );
    }

    public function schedule( Course $courses, $course_id ){
        $course    = $courses->with( ['schedules'] )->find( $course_id );
        ValidatePermission::authorizeWeb( $course, 'edit' );

        $schedules      = $course->schedules()->get()->groupedByDay();

        addJavascriptFile('assets/js/pages/course.schedule.create.js');
        addJavascriptFile('assets/plugins/custom/formrepeater/formrepeater.bundle.js');

        return view( 'pages.courses.create_schedule', [
            'course'    => $course,
            'days'      => Course::getCourseDays(),
            'schedules' => $schedules
        ]);
    }

    public function store_schedule( CourseScheduleStoreRequest $request, Course $courses, Schedule $schedule, $course_id ){
        $course     = $courses->find( $course_id );
        ValidatePermission::authorizeWeb( $course, 'edit' );

        $stored     = 0;
        foreach( $request->all()['schedule'] as $day => $schedule ){
            // get slots and create or update
            if( isset( $schedule['slots'] ) ){
                foreach( $schedule['slots'] as $slot ){
                    // if marked inactive then delete existing or continue
                    if( isset( $slot['active'] ) && $slot['active'] == 0 ){
                        if( isset( $slot['id'] ) && $slot['id'] > 0 && $course->schedules()->find( $slot['id'] )->delete() ){
                            $stored++;
                        }

                        continue;
                    }

                    // create or update slot
                    $slot['day']    = $day;
                    if( isset( $slot['id'] ) && $slot['id'] > 0 ){
                        $slotData   = $course->schedules()->find( $slot['id'] );
                        if( $slotData->update( $slot ) ){
                            $stored++;
                        }

                    }
                    else{
                        $store      = $course->schedules()->create( $slot );
                        if( $store->id > 0 ){
                            $stored++;
                        }
                    }
                }
            }
        }

        return $stored > 0
            ? $this->success( 'api/course.schedule.create.success' )
            : $this->error( 'api/course.schedule.create.failed' );
    }

    public function destroy( Course $courses, $course_id ){
        $course     = $courses->find( $course_id );
        ValidatePermission::authorizeWeb( $course, 'delete' );

        return $course->delete()
            ? $this->success( 'api/course.delete.success' )
            : $this->error( 'api/course.delete.failed' );
    }

    public function classes( Course $courses, $course_id ){
        $course     = $courses->find( $course_id );
        ValidatePermission::authorizeWeb( $course, 'index' );

        addJavascriptFile('assets/js/pages/course.class.create.js');

        return view('pages.courses.list_classes', [
            'course'            => $course,
        ]);
    }

    public function create_class( Course $courses, CoursesClass $coursesClasses, $course_id ){
        $course         = $courses->with( ['classes'] )->find( $course_id );
        ValidatePermission::authorizeWeb( $course, 'edit' );
        addJavascriptFile('assets/js/pages/course.class.create.js');

        $coursesClass   = $coursesClasses->where( 'course_id', $course_id )->get();

        return view('pages.courses.create_class', [
            'course'        => $course,
            'course_classes'=> $coursesClass
        ]);
    }

    public function store_class( CourseClassStoreRequest $request, Course $courses, $course_id ){
        $course     = $courses->find( $course_id );
        ValidatePermission::authorizeWeb( $course, 'edit' );

        $store      = $course->classes()->create( $request->validated() );
        return $store->id > 0
            ? $this->success( 'api/course.class.create.success' )
            : $this->error( 'api/course.class.create.failed' );
    }

    public function edit_class( Course $courses, CoursesClass $classes, $course_id, $class_id ){
        $course         = $courses->find( $course_id );
        $class          = $classes->find( $class_id );
        ValidatePermission::authorizeWeb( $course, 'edit' );
        addJavascriptFile('assets/js/pages/course.class.create.js');

        return view('pages.courses.edit_class', [
            'class'         => $class,
        ]);
    }

    public function update_class( CourseClassUpdateRequest $request, Course $courses, CoursesClass $classes, $course_id, $class_id ){
        $course     = $courses->find( $course_id );
        $class      = $classes->find( $class_id );
        ValidatePermission::authorizeWeb( $course, 'edit' );

        $updated    = $class->update( $request->validated() );
        return $updated
            ? $this->success( 'api/course.class.update.success' )
            : $this->error( 'api/course.class.update.failed' );
    }

    public function destroy_class( CourseClassDeleteRequest $request, Course $courses, CoursesClass $classes, $course_id, $class_id ){
        $course     = $courses->find( $course_id );
        $class      = $classes->find( $class_id );
        ValidatePermission::authorizeWeb( $course, 'delete' );

        return $class->delete()
            ? $this->success( 'api/course.class.delete.success' )
            : $this->error( 'api/course.class.delete.failed' );
    }
}
