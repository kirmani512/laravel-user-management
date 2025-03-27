@extends('portal.layouts.app')
@section('title')
    Districts
@endsection

@section('content')
<h2 class="mb-4">Districts</h2>

    <table id="districtTable" class="table table-bordered table-striped">

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
        <a class="btn btn-success" href="{{ route('district.create') }}" role="button">Add District</a>
    </div>

@endsection
@section('scripts')
{{-- <script>
    $(document).ready(function () {
        const table = $('#districtTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('district.list') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },

                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <a href="/district/edit/${row.id}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="/district/${row.id}/delete" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        `;
                    }
                }
            ]
        });
    });
</script> --}}
<script>
    $(function() {

        $('#districtTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('district.list') }}",
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
