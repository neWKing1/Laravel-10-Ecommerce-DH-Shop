<?php

namespace App\DataTables;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BrandDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('owner.brand.edit', $query->id) . "' class='btn btn-primary'>
                <i class='bi bi-pen'></i>
                </a>";
                $deleteBtn = "<a href='" . route('owner.brand.destroy', $query->id) . "' class='btn btn-danger ms-2 delete-item'>
                <i class='bi bi-archive'></i>
                </a>";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('is_featured', function ($query) {
                $active = "<span class='badge text-bg-success'>Yes</span>";
                $inActive = "<span class='badge text-bg-danger'>No</span>";
                if ($query->is_featured == 1) {
                    return $active;
                } else {
                    return $inActive;
                }
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    $button = "<div class='form-check form-switch'>
                    <input class='form-check-input change-status' data-id='" . $query->id . "'  type='checkbox' role='switch' id='flexSwitchCheckDefault' name='status' checked>
                  </div>";
                } else {
                    $button = "<div class='form-check form-switch'>
                    <input class='form-check-input change-status' data-id='" . $query->id . "'  type='checkbox' role='switch' id='flexSwitchCheckDefault' name='status'>
                  </div>";
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'is_featured'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Brand $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('brand-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                // Button::make('excel'),
                // Button::make('csv'),
                // Button::make('pdf'),
                // Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('is_featured'),
            Column::make('status'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Brand_' . date('YmdHis');
    }
}
