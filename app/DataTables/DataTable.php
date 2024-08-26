<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class DataTable extends \Yajra\DataTables\Services\DataTable{
    public function html(): HtmlBuilder{
        return $this->builder()
            ->setTableId( $this->getTableId() )
            ->columns( $this->getParsedColumns() )
            ->dom( $this->getDom() )
            ->ajax([ 'url' => $this->getRoute() ])
            ->parameters( $this->getParams( $this->getName(), $this->getOverRides() ));
    }

    protected function getTableId(){
        return 'kt_datatable';
    }

    protected function getDom(){
        return 'r<"row"t>
                    <"row"
                        <"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"
                            li
                        >
                        <"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>
                    >';
    }

    protected function getParams( $name, $overrides=[] ){
        $params = [
            'responsive'    => true,
            'stateSave'     => true,
            'scrollX'       => true,
            'pageLength'    => 25,
            'language'      => [
                'sLengthMenu'   => "_MENU_",
                'info'          => "Showing _START_ to _END_ of _TOTAL_ {$name}",
                "infoEmpty"     => "No {$name} found",
                "infoFiltered"  => "(0 out of _MAX_ {$name})",
                "processing"    => "Please wait..."
            ],
            'order'         => $this->getDefaultSorting(),
            'initComplete'  => $this->getInitComplete(),
            'stateLoaded'   => $this->getStateLoaded(),
            'columnDefs'    => [
                [ 'responsivePriority'    => 1, 'targets' => -1 ]
            ]
        ];

        // overrides from specific datatable
        if( !empty( $overrides ) ){
            foreach( $overrides as $key => $value ){
                $params[$key]   = $value;
            }
        }

        return $params;
    }

    protected function getInitComplete(){
        return 'function(){
            $("[name=\'kt_datatable_length\']").addClass("form-select-solid");
            $(".dataTables_scrollBody").css("overflow","visible");
        }';
    }

    protected function getStateLoaded(){
        return 'function(oSettings, oData){if( oData.search.search != "" ){$("*[data-table-filter=\"search\"]").val( oData.search.search );}}';
    }

    public function getParsedColumns(){
        $parsedColumns  = [];
        $columns        = $this->getColumns();
        if( !empty( $columns ) ){
            foreach( $columns as $column => $value ){
                $searchable = isset( $value['searchable'] )  ? $value['searchable'] : false;
                $orderable  = isset( $value['orderable'] )   ? $value['orderable'] : false;
                $content    = isset( $value['content'] ) ? $value['content'] : '';
                $data       = isset( $value['data'] ) ? $value['data'] : $column;
                $name       = isset( $value['name'] ) ? $value['name'] : $column;
                $title      = isset( $value['title'] ) ? $value['title'] : false;
                $renderer   = isset( $value['renderer'] ) ? $value['renderer'] : false;

                $parsedColumn   = Column::make( $column )
                                    ->searchable( $searchable )
                                    ->orderable( $orderable )
                                    ->content( $content )
                                    ->data( $data )
                                    ->name( $name );

                if( $title ){
                    $parsedColumn->title( $title );
                }

                if( $renderer ){
                    $parsedColumn->render( $renderer );
                }
                else{
                    $methodName     = 'render' . ucfirst( $column );
                    if( method_exists( $this, $methodName ) ){
                        $parsedColumn->render( call_user_func( [ $this, $methodName ] ) );
                    }
                }

                $parsedColumns[]    = $parsedColumn;
            }
        }

        // if we have actions column
        if( method_exists( $this, 'getActionColumn') ){
            $actionColumn   = call_user_func( [ $this, 'getActionColumn'] );
            if( $actionColumn ){
                $parsedColumns[]    = $actionColumn;
            }
        }

        return $parsedColumns;
    }

    protected function getDefaultSorting(){
        return [
            [ 0, 'desc' ]
        ];
    }

    protected function getActionColumn(){
        return Column::computed('action');
    }

    protected function filename():string{
        return $this->getName() . date('YmdHis');
    }

    protected function renderBoolean(){
        return "data == 1 ? 'Yes' : 'No'";
    }

    protected function getOverRides() : array { return []; }
    public function excel(){}
    public function csv(){}
    public function pdf(){}
}
