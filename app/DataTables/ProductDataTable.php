<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'product.action')
            ->addColumn('thumb image', function ($query) {
                return "<img src='" . asset($query->thumb_image) . "' width='100px' alt='...'>";
            })
            ->addColumn('type', function ($query) {
                switch ($query->product_type) {
                    case 'new_arrival':
                        return "<span class='badge text-bg-success'>New Arrival</span>";
                        break;
                    case 'featured':
                        return "<span class='badge text-bg-warning'>Featured Product</span>";
                        break;
                    case 'top_product':
                        return "<span class='badge text-bg-info'>Top Product</span>";
                        break;
                    case 'best_product':
                        return "<span class='badge text-bg-danger'>Best Product</span>";
                        break;
                    default:
                        break;
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
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('owner.product.edit', $query->id) . "' class='btn btn-primary'>
                <i class='bi bi-pen'></i>
                </a>";
                $deleteBtn = "<a href='" . route('owner.product.destroy', $query->id) . "' class='btn btn-danger ms-2 delete-item'>
                <i class='bi bi-archive'></i>
                </a>";
                $moreBtn = "
                <div class='dropdown d-inline ms-1'>
                    <button class='btn btn-secondary dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                    <i class='bi bi-gear'></i>
                    </button>
                    <ul class='dropdown-menu'>
                        <li><a class='dropdown-item' href='" . route('owner.product-image-gallery.index', ['product' => $query->id]) . "'>
                            <i class='bi bi-image'></i> Image Gallery
                        </a></li>
                        <li><a class='dropdown-item' href='" . route('owner.product-variant.index', ['product' => $query->id]) . "'>
                            <i class='bi bi-file'></i> Variants
                        </a></li>
                    </ul>
                </div>
                ";
                return $editBtn . $deleteBtn . $moreBtn;
            })
            ->rawColumns(['thumb image', 'type', 'status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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
            Column::make('price'),
            Column::make('thumb image'),
            Column::make('type'),
            Column::make('status'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
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
        return 'Product_' . date('YmdHis');
    }
}
