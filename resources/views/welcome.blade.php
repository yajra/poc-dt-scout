@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="users-table" class="table"></table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            let oTable = new DataTable('#users-table', {
                serverSide: true,
                processing: true,
                ajax: '',
                columns: [
                    {data: 'id', title: 'ID'},
                    {data: 'name', title: 'Name'},
                    {data: 'email', title: 'Email'},
                    {data: 'created_at', title: 'Created At'},
                    {data: 'updated_at', title: 'Updated At'},
                ],
            })

            $('#users-table').on('xhr.dt', function (e, settings, json, xhr) {
                if (json == null || !('disableOrdering' in json)) return;

                if (json.disableOrdering) {
                    oTable.settings()[0].aoColumns.forEach(function(column) {
                        column.bSortable = false;
                        $(column.nTh).removeClass('sorting_asc sorting_desc sorting').addClass('sorting_disabled');
                    });
                } else {
                    let changed = false;
                    oTable.settings()[0].aoColumns.forEach(function(column) {
                        if (column.bSortable) return;
                        column.bSortable = true;
                        changed = true;
                    });
                    if (changed) {
                        oTable.draw();
                    }
                }
            });
        })
    </script>
@endpush
