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

class ProductDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
     
    public const STATUS_FILTER_ID = 'product-status-filter';
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addcolumn('status', function ($product) {
                if ($product->status == '1')
                {
                    return '<div >
                              <span class="badge text-bg-success"> Enable </span>

                       </div>';

                }else{
                    return '<div >
                              <span class="badge text-bg-danger"> Disable </span>

                       </div>';
                }

            })


            ->addcolumn('display_homepage', function ($product) {
                if ($product->display_homepage == '1')
                {
                    return '<div >
                              <span class="badge text-bg-success">Enable</span>

                       </div>';

                }else{
                    return '<div >
                              <span class="badge text-bg-danger"> Disable</span>

                       </div>';
                }
            })

            ->addColumn('product_category', function ($product) {
                $options = '';
                foreach ($product->category()->pluck('title') as $category){
                    $options .= '<span class="badge text-bg-warning">  '.$category.'  </span>&nbsp ';
                }
                return $options;

            })

            ->addcolumn('other_products', function ($product) {
                if ($product->other_products == '1')
                {
                    return '<div >
                              <span class="badge text-bg-success"> Yes </span>

                       </div>';

                }else{
                    return '<div >
                              <span class="badge text-bg-danger"> Not </span>

                       </div>';
                }

            })
            ->addcolumn('by_style', function ($product) {
                if ($product->by_style == '1')
                {
                    return '<div >
                              <span class="badge text-bg-success"> Yes </span>

                       </div>';

                }else{
                    return '<div >
                              <span class="badge text-bg-danger"> Not </span>

                       </div>';
                }

            })

            ->editColumn('product_front_image', function ($product) {
                if ($product->getMedia('product_front_image')->isNotEmpty())
                {
                    foreach($product->getMedia('product_front_image') as $media)
                    {
                        return '<span><img src="'.$media->getUrl('s').'" class="img-thumbnail w-75" alt="avatar" ></span>';
                    }
                }
            })
            ->editColumn('product_back_image', function ($product) {
                if ($product->getMedia('product_back_image')->isNotEmpty())
                {
                    foreach($product->getMedia('product_back_image') as $media)
                    {
                        return '<span><img src="'.$media->getUrl('s').'" class="img-thumbnail w-75" alt="avatar" ></span>';
                    }
                }
            })

            ->addcolumn('view_product', function ($product) {
                return '<a target="_blank" href="'.config('app.url').'/'.$product->slug.'" class="btn btn-primary btn-sm">View product</a>';
            })


            ->addcolumn('action', function ($query) {
                $action = '<div class="dropdown d-inline-block">';
                $action .= '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-more-fill align-middle"></i></button>';
                $action .= '<ul class="dropdown-menu dropdown-menu-end">';
                $action .= '<li><a href="'.route('dashboard.product.edit', $query).'" class="dropdown-item"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>';
                $action .= '<li><a href="'.route('dashboard.product.delete', $query).'" onclick="return confirm(\'Are you sure?\')" class="dropdown-item remove-item-btn"><i class="ri-delete-bin-fill align-bottom me-2 text-danger"></i> Delete</a></li>';
                $action .= '</ul>';
                $action .= '</div>';
                return $action;

            })->rawColumns(['action','status','by_style','other_products','display_homepage','product_category','product_front_image','product_back_image','view_product']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model): QueryBuilder
    {
        return $this->applyStatusFilter(
            $model->newQuery()->with('category')->latest()
        );
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->addTableClass($this->tableClass)
            ->columns($this->getColumns())
            ->minifiedAjax('', $this->statusFilterScript(self::STATUS_FILTER_ID))
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
                    'targets'   => [5], // column index (start from 0)
                    'orderable' => false, // set orderable false for selected columns
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
            'name'         => ['data' => 'product_name', 'name' => 'product_name'],
            'category'       => ['data' => 'product_category', 'name' => 'product_category'],
            'by_style'       => ['data' => 'by_style', 'name' => 'by_style'],
            'product_front_image'       => ['data' => 'product_front_image', 'name' => 'product_front_image'],
            'product_back_image'       => ['data' => 'product_back_image', 'name' => 'product_back_image'],
            'status'         => ['data' => 'status', 'name' => 'status'],
            'display_homepage'         => ['data' => 'display_homepage', 'name' => 'display_homepage'],
            'slug'         => ['data' => 'slug', 'name' => 'slug'],
            'view_product'         => ['data' => 'view_product', 'name' => 'view_product'],
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
        return 'Product_' . date('YmdHis');
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
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
                [
                    'extend'        => 'excel',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
                [
                    'extend'        => 'print',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
                [
                    'extend'        => 'pdf',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
            ]
        ];
    }
}






