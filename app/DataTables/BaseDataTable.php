<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;

class BaseDataTable extends DataTable
{
    public $user;
    public $domHtml;
    public $tableClass;

    public function __construct()
    {
        /*$this->user = user();*/
        $this->domHtml = '<"row"<"col-md-12"<"row"<"col-md-4"l><"col-md-6"B><"col-md-2"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >';
        $this->tableClass = 'table table-hover dt-responsive nowrap align-middle mdl-data-table';
        $this->sInfo = 'Showing page _PAGE_ of _PAGES_ of _MAX_ entries';
        $this->lengthMenu = [[100, 200, 500, 1000, -1], [100, 200, 500, 1000, "All"]];
        $this->pageLength = 100;

    }
    
      /**
     * JS appended to the minifiedAjax payload so the status dropdown rendered by
     * `backend.partials.datatable-status-filter` travels with every draw.
     *
     * The surrounding closure yajra generates takes its payload as `data`, so
     * the snippet has to write onto that variable.
     */
    protected function statusFilterScript(string $selectId): string
    {
        return 'data.status = $("#' . $selectId . '").val();';
    }

    /**
     * Narrow a query by the status dropdown. "All" posts an empty string, so
     * only an explicit 0/1 is allowed to touch the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyStatusFilter($query, string $column = 'status')
    {
        $status = $this->request()->get('status');

        if ($status === null || $status === '' || !in_array($status, ['0', '1'], true)) {
            return $query;
        }

        return $query->where($column, (int) $status);
    }

}
