@extends('portal.layouts.app')

@section('title', 'User Profile')

@section('content')
    <h2>User Profile</h2>

    <table class="table">
        <tr>
            <th>Name:</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $user->email }}</td>
        </tr>
    </table>

    <a href="{{ route('user.profile.edit') }}" class="btn btn-success mt-6 d-inline-block">Edit Profile</a>
@endsection
