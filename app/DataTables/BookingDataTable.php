<?php

namespace App\DataTables;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class BookingDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ): EloquentDataTable{
        return ( new EloquentDataTable( $query ) )
            ->setRowId('id');
    }

    public function query( Booking $model ): QueryBuilder{
        return $model->newQuery()->with(['bookable','horse','trainer','user'])->withoutPackages();
    }

    protected function getRoute(){
        return route('acp.bookings.index');
    }

    protected function getName(){
        return 'bookings';
    }

    protected function getColumns(){
        return [
            'id'        => [
                'searchable'    => false,
                'orderable'     => true
            ],
            'created_at'=> [
                'searchable'    => false,
                'orderable'     => false,
                'title'         => 'Booking Date'
            ],
            'reference' => [
                'searchable'    => true,
                'orderable'     => false,
                'renderer'      => $this->renderReference()
            ],
            'user_id'   => [
                'searchable'    => true,
                'orderable'     => false,
                'data'          => 'user.name',
                'name'          => 'user.name',
                'title'         => 'Customer'
            ],
            'provider'  => [
                'searchable'    => true,
                'orderable'     => false,
                'data'          => 'bookable.provider_name'
            ],
            'service'   => [
                'searchable'    => true,
                'orderable'     => false,
                'data'          => 'bookable.name',
                'name'          => 'bookable.name',
            ],
            'start_time'    => [
                'searchable'    => false,
                'orderable'     => false,
                'title'         => 'Check In',
                'renderer'      => $this->renderNullDates(),
            ],
            'end_time'      => [
                'searchable'    => false,
                'orderable'     => false,
                'title'         => 'Check Out',
                'renderer'      => $this->renderNullDates(),
            ],
            'status'        => [
                'searchable'    => false,
                'orderable'     => false,
            ]
        ];
    }

    protected function getOverRides() : array {
        return [
            'columnDefs'    => [
                [ 'responsivePriority'    => 1, 'targets' => -1 ],
                [ 'responsivePriority'    => 0, 'targets' => 2 ]
            ]
        ];
    }

    protected function renderReference(){
        return "data.toUpperCase()";
    }

    protected function renderNullDates(){
        return "full['status'] == '" . Booking::getPendingStatus() . "' ? '" . __('acp/general.not_scheduled') ."' : data";
    }

    protected function getActionColumn(){
        return false;
    }
}
