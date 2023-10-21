<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('status', function($query){
                if($query->order_status == 1) {
                    return "Compiled";
                } else {
                    return  "Pending";
                }
            })
            ->addColumn('customer', function ($query) {
                return $query->user->name;
            })
            ->addColumn('date', function ($query) {
                return date('d-M-Y', strtotime($query->created_at));
            })
            ->addColumn('total', function ($query) {
                return $query->total . "$";
            })
            ->addColumn('action', function ($query) {
                $showBtn = "<a href='" . route('owner.order.show', $query->id) . "' class='btn btn-primary'>
                <i class='bi bi-eye'></i>
                </a>";
                $deleteBtn = "<a href='" . route('owner.order.destroy', $query->id) . "' class='btn btn-danger ms-2 delete-item'>
                <i class='bi bi-archive'></i>
                </a>";
                $statusBtn = "<a href='' class='btn btn-warning ms-2'>
                <i class='bi bi-truck'></i>
                </a>";
                return $showBtn . $deleteBtn . $statusBtn;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('order-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('invoice'),
            Column::make('customer'),
            Column::make('date'),
            Column::make('payment_method'),
            Column::make('total'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(180)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
