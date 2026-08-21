<?php

namespace App\DataTables;

use App\Models\VariationQuote;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class VariationQuoteDataTable extends BaseDataTable
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
            ->addColumn('created_at', function ($quote) {
                $date = $quote->created_at;
                return date_format($date, "g:ia \o\n l jS F Y");
            })
            ->addColumn('action', function ($quote) {
                $action = '<div class="dropdown d-inline-block">';
                $action .= '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-more-fill align-middle"></i></button>';
                $action .= '<ul class="dropdown-menu dropdown-menu-end">';
                $action .= '<li><a href="'.route('dashboard.orders.view', $quote->id).'" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-primary"></i> View Details</a></li>';
                $action .= '</ul>';
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VariationQuote $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VariationQuote $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('referrer_page', 'variation_quotes')
            ->with(['product', 'variation'])
            ->latest();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('variation-quote-table')
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
                    'targets'   => [5], // column index (start from 0) - action column
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
            'name'        => ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            'phone'       => ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            'email'       => ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            'quantity'    => ['data' => 'quantity', 'name' => 'quantity', 'title' => 'Quantity'],
            'created_at'  => ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Submitted At'],
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
        return 'VariationQuote_' . date('YmdHis');
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
                        'columns' => ':visible'
                    ]
                ],
                [
                    'extend'        => 'excel',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ],
                [
                    'extend'        => 'pdf',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ],
                [
                    'extend'        => 'print',
                    'className'     => 'btn',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ],
            ]
        ];
    }
}
