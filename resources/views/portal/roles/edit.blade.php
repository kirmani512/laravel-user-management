@extends('portal.layouts.app')
@section('title')
    Edit Role
@endsection

@section('content')
    <form method="POST" action="{{ route('role.update',$role->id) }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Edit Role</label>
                <input type="text" name="name" class="form-control" value="{{old('name',$role->name)}}">
            </div>
            <div class="form-group col-md-6">
                <h5>Permissions</h5>
                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input class="styled"
                            type="checkbox"
                            class="form-check-input"
                            id="permission_{{ $permission->id }}"
                            name="permissions[]"
                            value="{{ $permission->id }}">
                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                            {{$permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
@endsection
