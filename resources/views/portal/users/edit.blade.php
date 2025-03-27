@extends('portal.layouts.app')
@section('title')
    Edit User
@endsection

@section('content')
    <form method="POST" action="{{ route('user.update',$user->id) }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{old('nanme',$user->name)}}" placeholder="Enter name">
            </div>
            <div class="form-group col-md-6">
                <label for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" name="email" value="{{old('email',$user->email)}}" placeholder="Enter email">
            </div>
            <div class="form-group col-md-6">
                <label for="inputState">District</label>
                    <select id="inputState" class="form-control" name="district_id">
                        <option value="" selected>Select District</option>
                        @foreach ($districts as $district)
                        <option value="{{ $district->id }}" {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>

                        {{$district->name}}</option>
                        @endforeach

                    </select>
            </div>

            <div class="form-group col-md-6">

                <label for="role">Role</label>

                <select id="role" class="form-control" name="role">
                    <option selected>Assign Role</option>

                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>

                        {{ $role->name }}</option>
                    @endforeach
                </select>

            </div>

            <div class="form-group col-md-6">
                <label for="inputPassword4">Password</label>
                <input type="password" class="form-control" id="inputPassword4" name="password" placeholder="Password">
            </div>
            <div class="form-group col-md-6">
                <label for="salary">Salary</label>
                <input type="text" class="form-control" id="salary" name="salary" value="{{old('salary',$user->salary)}}" placeholder="Enter salary">
            </div>

        </div>

        <button type="submit" class="btn btn-success">Edit User</button>
    </form>
@endsection
