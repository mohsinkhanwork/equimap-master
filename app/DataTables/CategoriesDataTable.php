<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;

class CategoriesDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ): EloquentDataTable{
        return ( new EloquentDataTable( $query ) )
                    ->setRowId('id')
                    ->addColumn('action', 'pages.categories.listing_actions');
    }

    public function query( Category $model ): QueryBuilder{
        return $model->newQuery();
    }

    protected function getRoute(){
        return route('acp.categories.index');
    }

    protected function getName(){
        return 'categories';
    }

    protected function getColumns(){
        return [
            'id'        => [
                'searchable'    => false,
                'orderable'     => true
            ],
            'icon'      => [
                'searchable'    => false,
                'orderable'     => false,
                'data'          => 'icon.url',
                'content'       => 'Not uploaded',
            ],
            'name'      => [
                'searchable'    => true,
                'orderable'     => false,
            ],
            'slug'      => [
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

    protected function renderIcon(){
        $notUploaded    = __('acp/general.not_uploaded');
        return "typeof data != 'undefined' ? \"<img class='h-25px' src=\"+data+\" />\" : \"" . $notUploaded . "\";";
    }
}
