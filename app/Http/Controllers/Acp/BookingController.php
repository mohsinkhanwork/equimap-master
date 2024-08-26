<?php


namespace App\Http\Controllers\Acp;

use App\DataTables\BookingDataTable;
use App\Http\Controllers\Controller;

class BookingController extends Controller {
    public function index( BookingDataTable $dataTable ){
        return $dataTable->render('pages.bookings.index');
    }
}
