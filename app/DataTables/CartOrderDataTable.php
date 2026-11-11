<?php

namespace App\DataTables;

use App\Models\CartOrder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class CartOrderDataTable extends BaseDataTable
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
            ->editColumn('created_at', function ($order) {
                return date_format($order->created_at, "g:ia \o\n l jS F Y");
            })
            ->editColumn('status', function ($order) {
                $classes = [
                    'pending' => 'bg-warning-subtle text-warning',
                    'contacted' => 'bg-info-subtle text-info',
                    'completed' => 'bg-success-subtle text-success',
                    'cancelled' => 'bg-danger-subtle text-danger',
                ];
                $class = $classes[$order->status] ?? 'bg-secondary-subtle text-secondary';

                return '<span class="badge ' . $class . '">' . e(ucfirst($order->status)) . '</span>';
            })
            ->addColumn('action', function ($order) {
                $action = '<div class="dropdown d-inline-block">';
                $action .= '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-more-fill align-middle"></i></button>';
                $action .= '<ul class="dropdown-menu dropdown-menu-end">';
                $action .= '<li><a href="' . route('dashboard.cart-orders.view', $order->id) . '" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-primary"></i> View Details</a></li>';
                $action .= '</ul>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CartOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CartOrder $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('cart-order-table')
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
                    'targets'   => [7],
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
            'order_number'   => ['data' => 'order_number', 'name' => 'order_number', 'title' => 'Order #'],
            'name'           => ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            'email'          => ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            'phone'          => ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            'items_count'    => ['data' => 'items_count', 'name' => 'items_count', 'title' => 'Items'],
            'total_quantity' => ['data' => 'total_quantity', 'name' => 'total_quantity', 'title' => 'Units'],
            'status'         => ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            'created_at'     => ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Placed At'],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('Action'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CartOrders_' . date('YmdHis');
    }

    protected function buttons()
    {
        return [
            'buttons' => [
                ['extend' => 'copy', 'className' => 'btn'],
                ['extend' => 'csv', 'className' => 'btn', 'exportOptions' => ['columns' => ':visible']],
                ['extend' => 'excel', 'className' => 'btn', 'exportOptions' => ['columns' => ':visible']],
                ['extend' => 'pdf', 'className' => 'btn', 'exportOptions' => ['columns' => ':visible']],
                ['extend' => 'print', 'className' => 'btn', 'exportOptions' => ['columns' => ':visible']],
            ],
        ];
    }
}
