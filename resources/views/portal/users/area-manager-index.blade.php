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
                <th>Employess In District</th>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>


@endsection
@section('scripts')
    <script>
        $(function() {

            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('areamanagerDetails.index') }}",
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

                        data: 'employees_in_district', title: 'Employees In District',
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
