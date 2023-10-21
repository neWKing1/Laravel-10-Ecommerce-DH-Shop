<?php

namespace App\DataTables;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductVariantDataTable extends DataTable
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
                $editBtn = "<a href='" . route('owner.product-variant.edit', $query->id) . "' class='btn btn-primary'>
            <i class='bi bi-pen'></i>
            </a>";
                $deleteBtn = "<a href='" . route('owner.product-variant.destroy', $query->id) . "' class='btn btn-danger ms-2 delete-item'>
            <i class='bi bi-archive'></i>
            </a>";
            
                $manageOption = "<a href='" . route('owner.product-variant-item.index', ['productId' => request()->product, 'variantId' => $query->id]) . "' class='btn btn-secondary me-2'>
                <i class='bi bi-pencil-square'></i> Variant Items
                </a>";
                return $manageOption . $editBtn . $deleteBtn;
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductVariant $model): QueryBuilder
    {
        return $model->where('product_id', request()->product)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('productvariant-table')
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
            Column::make('status'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(250)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductVariant_' . date('YmdHis');
    }
}
