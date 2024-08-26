<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\ServicesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ServiceHorseStoreRequest;
use App\Http\Requests\Service\ServiceScheduleDayStoreRequest;
use App\Http\Requests\Service\ServiceScheduleHourStoreRequest;
use App\Http\Requests\Service\ServiceStoreRequest;
use App\Http\Requests\Service\ServiceTrainerStoreRequest;
use App\Http\Requests\Service\ServiceUpdateRequest;
use App\Models\Category;
use App\Models\Horse;
use App\Models\Provider;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\Trainer;

class ServiceController extends Controller {
    public function index( ServicesDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Service::class );
        return $dataTable->render('pages.services.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Service::class );
        addJavascriptFile('assets/js/pages/service.create.js');

        return view('pages.services.create', [
            'providers' => Provider::all(),
            'categories'=> Category::all(),
            'units'     => Service::getServiceUnits()
        ]);
    }

    public function store( ServiceStoreRequest $request, Service $services ){
        ValidatePermission::authorizeWeb( Service::class, 'create' );

        $store      = $services->create( $request->validated() );
        return $store->id > 0
                    ? $this->success( 'api/service.create.success', $store )
                    : $this->error( 'api/service.create.failed' );
    }

    public function edit( Service $services, $service_id ){
        $service    = $services->find( $service_id );
        ValidatePermission::authorizeWeb( $service );
        addJavascriptFile('assets/js/pages/service.create.js');

        return view('pages.services.edit', [
                'providers' => Provider::all(),
                'categories'=> Category::all(),
                'units'     => Service::getServiceUnits(),
                'service' => $service
            ]);
    }

    public function update( ServiceUpdateRequest $request, Service $services, $service_id ){
        $service    = $services->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );

        $updated    = $service->update( $request->validated() );
        return $updated
                        ? $this->success( 'api/service.update.success' )
                        : $this->error( 'api/service.update.failed' );
    }

    public function schedule( Service $services, $service_id ){
        $service    = $services->with( ['schedules'] )->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );

        if( $service->unit == 'hour'){
            $schedules      = $service->schedules()->get()->groupedByDay();
            $viewTemplate   = 'pages.services.create_schedule_hour';

            addJavascriptFile('assets/js/pages/service.schedule.hour.create.js');
            addJavascriptFile('assets/plugins/custom/formrepeater/formrepeater.bundle.js');
        }
        else{
            $schedules      = $service->schedules()->get()->groupedByDaySingle();
            $viewTemplate   = 'pages.services.create_schedule_day';

            addJavascriptFile('assets/js/pages/service.schedule.day.create.js');
        }

        return view( $viewTemplate, [
                        'service'   => $service,
                        'days'      => $service->getServiceDays(),
                        'schedules' => $schedules
                    ]);
    }

    public function store_schedule_day( ServiceScheduleDayStoreRequest $request, Service $service, $service_id ){
        $service    = $service->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );

        $stored     = 0;
        foreach( $request->validated()['schedule'] as $schedule ){
            // if id is available and schedule is de-activated than delete it
            if( isset( $schedule['active'] ) && $schedule['active'] == 0 ){
                if( isset( $schedule['id'] ) && $service->schedules()->find( $schedule['id'] )->delete() ){
                    $stored++;
                }

                continue;
            }

            // if id is available than update and continue
            if( isset( $schedule['id'] ) && $schedule['id'] > 0 ){
                if( $service->schedules()->find( $schedule['id'] )->update( $schedule ) ){
                    $stored++;
                    continue;
                };
            }

            // lastly if all checks well and no id is available than create new schedule
            $store      = $service->schedules()->create( $schedule );
            if( $store->id > 0 ){
                $stored++;
            }
        }

        return $stored > 0
                    ? $this->success( 'api/service.schedule.create.success' )
                    : $this->error( 'api/service.schedule.create.failed' );
    }

    public function store_schedule_hour( ServiceScheduleHourStoreRequest $request, Service $service, Schedule $schedule, $service_id ){
        $service    = $service->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );

        $stored     = 0;
        foreach( $request->all()['schedule'] as $day => $schedule ){
            // get slots and create or update
            if( isset( $schedule['slots'] ) ){
                foreach( $schedule['slots'] as $slot ){
                    // if marked to delete then delete existing or continue
                    if( isset( $slot['delete'] ) && $slot['delete'] == 1 ){
                        if( isset( $slot['id'] ) && $slot['id'] > 0 && $service->schedules()->find( $slot['id'] )->delete() ){
                            $stored++;
                        }
                        continue;
                    }

                    // create or update slot
                    $slot['day']    = $day;
                    if( isset( $slot['id'] ) && $slot['id'] > 0 ){
                        $slotData   = $service->schedules()->find( $slot['id'] );
                        if( $slotData->update( $slot ) ){
                            $stored++;
                        }

                    }
                    else{
                        $store      = $service->schedules()->create( $slot );
                        if( $store->id > 0 ){
                            $stored++;
                        }
                    }
                }
            }
        }

        return $stored > 0
                    ? $this->success( 'api/service.schedule.create.success' )
                    : $this->error( 'api/service.schedule.create.failed' );
    }

    public function horses( Service $services, Horse $horses, $service_id ){
        $service        = $services->with( ['horses'] )->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );
        addJavascriptFile('assets/js/pages/service.horse.create.js');

        $horses         = $horses->where( 'provider_id', $service->provider_id )->get();
        $serviceHorses  = $service->horses->pluck('id')->toArray();

        return view('pages.services.create_horses', [
            'service'       => $service,
            'horses'        => $horses,
            'service_horses'=> $serviceHorses
        ]);
    }

    public function store_horses( ServiceHorseStoreRequest $request, Service $services, $service_id ){
        $service    = $services->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );

        $updated    = false;
        if( $request->has('horse') ){
            $updated    = $service->service_horses()->sync( $request->horse );
        }

        return $updated
                    ? $this->success( 'api/service.horses.added.success' )
                    : $this->error( 'api/service.horses.added.failed' );
    }

    public function trainers( Service $services, Trainer $trainers, $service_id ){
        $service        = $services->with( ['horses'] )->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );
        addJavascriptFile('assets/js/pages/service.trainer.create.js');

        $trainers       = $trainers->where( 'provider_id', $service->provider_id )->get();
        $serviceTrainers= $service->trainers->pluck('id')->toArray();

        return view('pages.services.create_trainers', [
            'service'           => $service,
            'trainers'          => $trainers,
            'service_trainers'  => $serviceTrainers
        ]);
    }

    public function store_trainers( ServiceTrainerStoreRequest $request, Service $services, $service_id ){
        $service    = $services->find( $service_id );
        ValidatePermission::authorizeWeb( $service, 'edit' );

        $updated      = false;
        if( $request->has('trainer') ){
            $updated    = $service->service_trainers()->sync( $request->trainer );
        }

        return $updated
                    ? $this->success( 'api/service.trainers.created.success' )
                    : $this->error( 'api/service.trainers.created.failed' );
    }
}
