<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UserDataTable extends DataTable{
    public function dataTable( QueryBuilder $query ): EloquentDataTable{
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('action', 'pages.users.listing_actions');
    }

    public function query(User $model): QueryBuilder{
        return $model->newQuery()->with(['roles']);
    }

    protected function getRoute(){
        return route('acp.users.index');
    }

    protected function getName(){
        return 'users';
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
            'roles'     => [
                'searchable'    => false,
                'orderable'     => false,
                'content'       => __('acp/general.unassigned'),
                'renderer'      => $this->renderRoles()
            ],
            'login'     => [
                'searchable'    => false,
                'orderable'     => false,
            ],
            'login_verified'    => [
                'searchable'    => false,
                'orderable'     => false,
                'renderer'      => $this->renderLoginVerified(),
                'title'         => __('acp/general.status'),
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

    protected function renderLoginVerified(){
        $verified   = __('acp/general.verified');
        $unverified = __('acp/general.unverified');
        return "data == 1 ? '<span class=\"badge badge-success\">{$verified}</span>' : '<span class=\"badge badge-light-primary\">{$unverified}</span>'";
    }

    protected function renderRoles(){
        $unassigned = __('acp/general.unassigned');
        return "data != ''
                    ? data.map( u => '<span class=\"text-capitalize badge badge-light ms-1\">' + u.name + '</span>' ).join('')
                    : '<span class=\"text-capitalize badge badge-warning\">{$unassigned}</span>'";
    }
}
