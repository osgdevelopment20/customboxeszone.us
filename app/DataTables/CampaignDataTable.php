<?php

namespace App\DataTables;

use App\Http\Controllers\frontend\PagesController;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CampaignDataTable extends BaseDataTable
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


            ->editColumn('mms', function ($query) {

                if ($query->type == 'mms'){
                    return '<span><img src="' . '/campaign/'.$query->mms . '" class="img-thumbnail " width="150" alt="avatar"></span>';
                }else{
                    return '';
                }

            })

            ->editColumn('type', function ($query) {

                if ($query->type === 'sms'){
                    return '<div >
                              <span class="badge bg-success"> SMS </span>

                       </div>';
                }else
                {
                    return '<div >
                              <span class="badge bg-success"> MMS </span>
                       </div>';
                }

            })





            ->addcolumn('action', function ($query) {
                $action = '<div class="dropdown d-inline-block">';
                $action .= '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-more-fill align-middle"></i></button>';
                $action .= '<ul class="dropdown-menu dropdown-menu-end">';
                $action .= '<li><a href="'.route('dashboard.campaign.edit', $query).'" class="dropdown-item"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>';
                $action .= '<li><a href="'.route('dashboard.campaign.delete', $query).'" class="dropdown-item remove-item-btn"><i class="ri-delete-bin-fill align-bottom me-2 text-danger"></i> Delete</a></li>';
                $action .= '</ul>';
                $action .= '</div>';
                return $action;

            })->rawColumns(['action','mms','type']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Campaign $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Campaign $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('campaign-table')
            ->addTableClass($this->tableClass)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->dom($this->domHtml)
            ->parameters([
                'buttons' => $this->buttons(),
                'oLanguage' => [
                    'oPaginate' => [
                        'sPrevious' => '<i class="ri-arrow-left-line ri-1x"> </i>',
                        'sNext' => '<i class="ri-arrow-right-line ri-1x"> </i>',
                    ],
                    'sInfo' => $this->sInfo,
                    'sSearch' => '',
                    'sSearchPlaceholder' => 'Search...',
                    'sLengthMenu' => 'Results :  _MENU_',
                ],
                'columnDefs' => [
                    'targets' => [5], // column index (start from 0)
                    'orderable' => false, // set orderable false for selected columns
                ],
                'stripeClasses' => [],
                'lengthMenu'     => $this->lengthMenu,
                'pageLength'     => $this->pageLength,
                'processing' => true,
                'autoWidth' => true,
                'serverSide' => true,
                'responsive' => true,
                'searchable' => true,
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
            'name' => ['data' => 'name', 'name' => 'name'],
            'type' => ['data' => 'type', 'name' => 'type'],
            'body' => ['data' => 'body', 'name' => 'body'],
            'mms' => ['data' => 'mms', 'name' => 'mms'],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
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
        return 'Campaign_' . date('YmdHis');
    }

    protected function buttons()
    {
        return [
            'buttons' => [
                [
                    'extend' => 'copy',
                    'className' => 'btn'
                ],
                [
                    'extend' => 'csv',
                    'className' => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
                [
                    'extend' => 'excel',
                    'className' => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
                [
                    'extend' => 'print',
                    'className' => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
                [
                    'extend' => 'pdf',
                    'className' => 'btn',
                    'exportOptions' => [
                        'columns' => [0, 1, 2, 3, 4, 5, 6, 7]
                    ]
                ],
            ]
        ];
    }
}
