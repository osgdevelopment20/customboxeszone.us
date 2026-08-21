{{--
    Status dropdown for a server-side datatable.

    $table — the datatable's table id (e.g. "product-table"), used to look the
             instance up in window.LaravelDataTables so the change handler can
             redraw it.
    $id    — the select's own id; the datatable class must send the same id in
             its minifiedAjax script (see BaseDataTable::statusFilterScript).
--}}
<div class="row px-3 pb-3">
    <div class="col-md-3">
        <label for="{{ $id }}" class="form-label mb-1">Status</label>
        <select id="{{ $id }}" class="form-select form-select-sm">
            <option value="">All</option>
            <option value="1">Enable</option>
            <option value="0">Disable</option>
        </select>
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            // Read the table at event time rather than on load: this stack is
            // rendered alongside the datatable's own init script and the order
            // between the two is not guaranteed.
            $('#{{ $id }}').on('change', function () {
                var table = window.LaravelDataTables && window.LaravelDataTables['{{ $table }}'];

                if (table) {
                    table.ajax.reload();
                }
            });
        });
    </script>
@endpush
