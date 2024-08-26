<?php

namespace App\DataTables;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;

class BannerDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ): EloquentDataTable{
        return ( new EloquentDataTable( $query ) )
            ->setRowId('id')
            ->addColumn('action', 'pages.banners.listing_actions');
    }

    public function query( Banner $model ): QueryBuilder{
        return $model->newQuery();
    }

    protected function getRoute(){
        return route('acp.banners.index');
    }

    protected function getName(){
        return 'banners';
    }

    protected function getColumns(){
        return [
            'id'        => [
                'searchable'    => false,
                'orderable'     => true
            ],
            'active'      => [
                'searchable'    => false,
                'orderable'     => false,
                'renderer'      => $this->renderBoolean()
            ],
            'name'      => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'type'      => [
                'searchable'    => false,
                'orderable'     => false,
            ],
            'sort'      => [
                'searchable'    => false,
                'orderable'     => true,
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
