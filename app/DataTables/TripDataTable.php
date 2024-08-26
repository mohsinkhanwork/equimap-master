<?php

namespace App\DataTables;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;

class TripDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ): EloquentDataTable{
        return ( new EloquentDataTable( $query ) )
                    ->setRowId('id')
                    ->orderColumn( 'id', '-id' )
                    ->addColumn('action', 'pages.trips.listing_actions');
    }

    public function query( Trip $model ): QueryBuilder{
        return $model->newQuery()->with(['category','destination']);
    }

    protected function getRoute(){
        return route('acp.trips.index');
    }

    protected function getName(){
        return 'events';
    }

    protected function getColumns(){
        return [
            'id'        => [
                'searchable'    => false,
                'orderable'     => true
            ],
            'active'    => [
                'searchable'    => false,
                'orderable'     => false,
                'renderer'      => $this->renderBoolean()
            ],
            'approved'  => [
                'searchable'    => false,
                'orderable'     => false,
                'renderer'      => $this->renderBoolean()
            ],
            'name'      => [
                'searchable'    => true,
                'orderable'     => false,
                'renderer'      => $this->renderName()
            ],
            'price'     => [
                'searchable'    => false,
                'orderable'     => true,
                'renderer'      => $this->renderPrice()
            ],
            'destination'  => [
                'searchable'    => true,
                'orderable'     => false,
                'data'          => 'destination.name',
                'name'          => 'destination.name',
            ],
            'category'  => [
                'searchable'    => true,
                'orderable'     => false,
                'data'          => 'category.name',
                'name'          => 'category.name',
            ],
            'sort'      => [
                'searchable'    => false,
                'orderable'     => true,
            ]
        ];
    }

    protected function renderPrice(){
        return "data + ' ' + full['currency']";
    }

    protected function renderName(){
        return "type === 'display' && data.length > 40 ? data.substr( 0, 40 ) +'…' : data;";
    }

    protected function getOverRides() : array {
        return [
            'columnDefs'    => [
                [ 'responsivePriority'    => 1, 'targets' => -1 ],
                [ 'responsivePriority'    => 0, 'targets' => 2 ]
            ]
        ];
    }
}
