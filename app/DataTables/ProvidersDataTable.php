<?php

namespace App\DataTables;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;


class ProvidersDataTable extends DataTable{
    public function dataTable(QueryBuilder $query): EloquentDataTable{
        return ( new EloquentDataTable( $query ) )
            ->setRowId('id')
            ->addColumn('action', 'pages.providers.listing_actions');
    }

    public function query(Provider $model): QueryBuilder{
        return $model->newQuery();
    }

    protected function getRoute(){
        return route('acp.providers.index');
    }

    protected function getName(){
        return 'providers';
    }

    protected function getColumns(){
        return [
            'id'        => [
                'searchable'    => false,
                'orderable'     => true
            ],
            'name'      => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'address'   => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'city'      => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'country'   => [
                'searchable'    => true,
                'orderable'     => false,
            ]
        ];
    }
}
