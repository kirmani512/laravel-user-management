@extends('portal.layouts.app')
@section('title')
    Users
@endsection

@section('content')
    <h2 class="mb-4">Users</h2>

    <table id="userTable" class="table table-bordered table-striped">

        <thead>

            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>District</th>
                <th>Role</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>
    {{-- <div>
        <a class="btn btn-success" href="{{ route('user.create') }}" role="button">Add User</a>
    </div> --}}
    @can('manage_users')
        <div>
            <a class="btn btn-success" href="{{ route('user.create') }}" role="button">Add User</a>
        </div>
    @endcan
@endsection
@section('scripts')
    <script>
        $(function() {

            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.list') }}",
                responsive: true,
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'district'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'salary'
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
