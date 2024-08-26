<?php

namespace App\DataTables;

use App\Models\Package;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;

class PackageDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ){
        return ( new EloquentDataTable( $query ) )
            ->setRowId('id')
            ->orderColumn( 'id', '-id' )
            ->addColumn('action', 'pages.packages.listing_actions');
    }

    public function query( Package $model ){
        return $model->newQuery();
    }

    protected function getRoute(){
        return route('acp.packages.index');
    }

    protected function getName(){
        return 'packages';
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
            'approved'  => [
                'searchable'    => false,
                'orderable'     => false,
                'renderer'      => $this->renderBoolean(),
            ],
            'name'      => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'type'      => [
                'searchable'    => true,
                'orderable'     => false,
                'data'          => 'package_type'
            ],
            'sort'      => [
                'searchable'    => false,
                'orderable'     => false,
            ],
            'price'     => [
                'searchable'    => false,
                'orderable'     => false,
            ],
            'quantity'  => [
                'searchable'    => false,
                'orderable'     => false,
            ]
        ];
    }
}
