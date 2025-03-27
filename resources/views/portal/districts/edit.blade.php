@extends('portal.layouts.app')
@section('title')
    Edit District
@endsection

@section('content')
    <form method="POST" action="{{ route('district.update',$district->id) }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">District Name</label>
                <input type="text" name="name" class="form-control" value="{{old('name',$district->name)}}">
            </div>
            {{-- <div class="form-group col-md-4">
                <label for="inputState">Area Manager</label>
                <select id="inputState" class="form-control">
                    <option selected>Assign Manager</option>
                    <option>...</option>
                </select>
            </div> --}}
        </div>




        <button type="submit" class="btn btn-success">Edit District</button>
    </form>
@endsection
