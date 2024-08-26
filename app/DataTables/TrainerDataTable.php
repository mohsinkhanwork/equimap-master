<?php

namespace App\DataTables;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;

class TrainerDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ){
        return ( new EloquentDataTable( $query ) )
            ->setRowId('id')
            ->orderColumn( 'id', '-id' )
            ->addColumn('action', 'pages.trainers.listing_actions');
    }

    public function query( Trainer $model ){
        return $model->newQuery()->with(['provider']);
    }

    protected function getRoute(){
        return route('acp.trainers.index');
    }

    protected function getName(){
        return 'trainers';
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
                'renderer'      => $this->renderBoolean(),
            ],
            'name'      => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'phone'     => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'provider'  => [
                'searchable'    => true,
                'orderable'     => false,
                'data'          => 'provider.name',
                'name'          => 'provider.name'
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
}
