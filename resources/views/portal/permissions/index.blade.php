@extends('portal.layouts.app')
@section('title')
    Permissions
@endsection

@section('content')
<h2 class="mb-4">Permissions</h2>

    <table id="permissionTable" class="table table-bordered table-striped">

        <thead>

            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>
    <div>
        <a class="btn btn-success" href="{{ route('permission.create') }}" role="button">Add Permission</a>
    </div>

@endsection
@section('scripts')
<script>
    $(function() {

        $('#permissionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('permission.list') }}",
            responsive: true,
            columns: [{
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    targets: -1,
                    data: 'action',
                },
            ],
            // columnDefs: [{
            //     'targets': [2], // column index (start from 0)
            //     'orderable': false, // set orderable false for selected columns
            // }],
            order: [
                [0, 'desc']
            ],
        });
    });
</script>
@endsection
