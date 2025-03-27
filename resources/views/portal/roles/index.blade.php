@extends('portal.layouts.app')

@section('title')
    Roles
@endsection

@section('content')
    <div class="container">
        <h2 class="mb-4">Roles</h2>
        <table id="roleTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div>
            <a class="btn btn-success" href="{{ route('role.create') }}" role="button">Add Role</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {

            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('role.list') }}",
                responsive: true,
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'role_name'
                    },
                    {
                        data: 'permissions'
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
