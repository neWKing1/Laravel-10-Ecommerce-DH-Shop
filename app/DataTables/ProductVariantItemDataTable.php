<?php

namespace App\DataTables;

use App\Models\ProductVariantItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductVariantItemDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
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
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('owner.product-variant-item.edit', $query->id) . "' class='btn btn-primary'>
            <i class='bi bi-pen'></i>
            </a>";
                $deleteBtn = "<a href='" . route('owner.product-variant-item.destroy', $query->id) . "' class='btn btn-danger ms-2 delete-item'>
            <i class='bi bi-archive'></i>
            </a>";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('is default', function ($query) {
                if ($query->is_default == 1) {
                    return "<span class='badge text-bg-success'>Default</span>";
                } else {
                    return "<span class='badge text-bg-danger'>No</span>";
                }
            })
            ->rawColumns(['action', 'status', 'is default'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductVariantItem $model): QueryBuilder
    {
        return $model->where('product_variant_id', request()->variantId)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('productvariantitem-table')
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
            Column::make('is default'),
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
        return 'ProductVariantItem_' . date('YmdHis');
    }
}
