@extends('portal.layouts.app')
@section('title')
   Edit Permission
@endsection

@section('content')
    <form method="POST" action="{{ route('permission.update',$permission->id) }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Edit Permission</label>
                <input type="text" name="name" class="form-control" value="{{old('name',$permission->name)}}">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
@endsection
