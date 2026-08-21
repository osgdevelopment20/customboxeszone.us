<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class ProductVariationDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('product_name', function ($product) {
                return $product->product_name;
            })
            ->addColumn('variations_count', function ($product) {
                return '<span class="badge bg-primary">' . $product->variations_count . ' Variation(s)</span>';
            })
            ->addColumn('status', function ($product) {
                $activeCount = $product->variations()->where('status', 1)->count();
                $inactiveCount = $product->variations()->where('status', 0)->count();

                $html = '';
                if ($activeCount > 0) {
                    $html .= '<span class="badge text-bg-success">' . $activeCount . ' Active</span> ';
                }
                if ($inactiveCount > 0) {
                    $html .= '<span class="badge text-bg-danger">' . $inactiveCount . ' Inactive</span>';
                }
                return $html ?: '<span class="text-muted">No variations</span>';
            })
            ->addColumn('sample_image', function ($product) {
                $firstVariation = $product->variations()->first();
                if ($firstVariation && $firstVariation->getMedia('variation_image')->isNotEmpty()) {
                   return '<span><img src="' .  $firstVariation->getFirstMediaUrl('variation_image')  . '" class="img-thumbnail" style="max-width: 80px;" alt="variation"></span>';
                }
                return '<span class="text-muted">No Image</span>';
            })

            ->addColumn('action', function ($product) {
                $action = '<div class="dropdown d-inline-block">';
                $action .= '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-more-fill align-middle"></i></button>';
                $action .= '<ul class="dropdown-menu dropdown-menu-end">';
                $action .= '<li><a href="' . route('dashboard.variation.edit', ['product' => $product->id]) . '" class="dropdown-item"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit Variations</a></li>';
                $action .= '</ul>';
                $action .= '</div>';
                return $action;
            })
            ->setRowId('id')
            ->rawColumns(['action', 'status', 'sample_image', 'variations_count']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('products.*')
            ->withCount('variations')
            ->has('variations')
            ->orderBy('products.id', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('variation-table')
            ->addTableClass($this->tableClass)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->dom($this->domHtml)
            ->parameters([
                'buttons'        => $this->buttons(),
                'oLanguage'      => [
                    'oPaginate'          => [
                        'sPrevious' => '<i class="ri-arrow-left-line ri-1x"> </i>',
                        'sNext'     => '<i class="ri-arrow-right-line ri-1x"> </i>',
                    ],
                    'sInfo'              => $this->sInfo,
                    'sSearch'            => '',
                    'sSearchPlaceholder' => 'Search...',
                    'sLengthMenu'        => 'Results :  _MENU_',
                ],
                'columnDefs'     => [
                    'targets'   => [4], // column index for action column
                    'orderable' => false,
                ],
                'stripeClasses'  => [],
                'lengthMenu'     => $this->lengthMenu,
                'pageLength'     => $this->pageLength,
                'processing'     => true,
                'autoWidth'      => true,
                'serverSide'     => true,
                'responsive'     => true,
                'searchable'     => true,
                'fnDrawCallback' => 'function() {
                    $("[data-bs-toggle=\'tooltip\']").tooltip();
                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            'product_name' => ['data' => 'product_name', 'name' => 'product_name', 'title' => 'Product'],
            'variations_count' => ['data' => 'variations_count', 'name' => 'variations_count', 'title' => 'Total Variations'],
            Column::computed('sample_image')
                ->title('Sample Image')
                ->orderable(false)
                ->searchable(false),
            'status'       => ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ProductVariation_' . date('YmdHis');
    }

    protected function buttons()
    {
        return [
            'buttons' => [
                [
                    'extend'    => 'copy',
                    'className' => 'btn'
                ],
                [
                    'extend'        => 'csv',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 3]
                    ]
                ],
                [
                    'extend'        => 'excel',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 3]
                    ]
                ],
            ],
        ];
    }
}
