<?php

namespace App\DataTables;

use App\Models\Horse;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;


class HorseDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ): EloquentDataTable{
        return ( new EloquentDataTable( $query ) )
                    ->setRowId('id')
                    ->orderColumn( 'id', '-id' )
                    ->addColumn('action', 'pages.horses.listing_actions');
    }

    public function query( Horse $model ): QueryBuilder{
        return $model->newQuery()->with(['provider']);
    }

    protected function getRoute(){
        return route('acp.horses.index');
    }

    protected function getName(){
        return 'horses';
    }

    protected function getColumns(){
        return [
            'id'        => [
                'searchable'    => false,
                'orderable'     => true,
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
