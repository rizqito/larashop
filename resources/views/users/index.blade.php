@extends('layouts.global')
@section('title') Users list @endsection
@section('content')
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('users.index') }}">
                <div class="input-group mb-3">
                    <input type="text" value="{{ Request::get('keyword') }}" name="keyword" class="form-control col-md-10" placeholder="Filter berdasarkan email">
                    <div class="input-group-append">
                        <input type="submit" value="Filter" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><b>Name</b></th>
                <th><b>Username</b></th>
                <th><b>Email</b></th>
                <th><b>Avatar</b></th>
                <th><b>Action</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $u)
            <tr>
                <td> {{$u->name}} </td>
                <td> {{$u->username}} </td>
                <td> {{$u->email}} </td>
                <td>
                    @if($u->avatar)
                        <img src="{{ asset('storage/'.$u->avatar) }}" width="70px">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <a href="{{ route('users.edit',$u->id) }}" class="btn btn-info text-white btn-sm">Edit</a>
                    <form onsubmit="return confirm('Delete this user permanently?')" class="d-inline" action="{{ route('users.destroy', $u->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                    <a href="{{ route('users.show',$u->id) }}" class="btn btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
@endsection