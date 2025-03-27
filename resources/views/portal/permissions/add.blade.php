@extends('portal.layouts.app')
@section('title')
    Add Permission
@endsection

@section('content')
    <form method="POST" action="{{ route('permission.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Add Permission</label>
                <input type="text" name="permission" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
@endsection
