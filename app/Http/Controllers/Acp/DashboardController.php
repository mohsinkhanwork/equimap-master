<?php


namespace App\Http\Controllers\Acp;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Horse;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Trainer;

class DashboardController extends Controller {
    public function index(){
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);

        return view('pages.dashboards.index', [
            'counters'  => $this->getCounters(),
            'bookings'  => $this->getBookings(),
        ]);
    }

    protected function getCounters(){
        return [
            'services'  => Service::all()->count(),
            'providers' => Provider::all()->count(),
            'horses'    => Horse::all()->count(),
            'trainers'  => Trainer::all()->count()
        ];
    }

    protected function getBookings(){
        $bookings   = Booking::whereDate( 'start_time', '>=', utils()->currentTime() )
                    ->where('status', 'scheduled')
                    ->withoutPackages()
                    ->orderBy('start_time');

        return [
            'data'  => $bookings->limit( 3 )->get(),
            'count' => $bookings->count()
        ];
    }
}
