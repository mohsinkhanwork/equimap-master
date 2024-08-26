<?php

namespace App\DataTables;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;

class PageDataTable extends DataTable{
    public function dataTable(QueryBuilder $query): EloquentDataTable{
        return ( new EloquentDataTable( $query ) )
            ->setRowId('id')
            ->addColumn('action', 'pages.pages.listing_actions');
    }

    public function query( Page $model ): QueryBuilder{
        return $model->newQuery();
    }

    protected function getRoute(){
        return route('acp.pages.index');
    }

    protected function getName(){
        return 'pages';
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
            'name'      => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'slug'      => [
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
}
