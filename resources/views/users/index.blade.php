@extends('layouts.global')
@section('title') Users list @endsection
@section('content')
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('users.index')}}">
                <div class="row">
                    <div class="col-md-6">
                        <input value="{{Request::get('keyword')}}" name="keyword" class="form-control" type="text" placeholder="Masukan email untuk filter..."/>
                    </div>
                    <div class="col-md-6">
                        <input {{Request::get('status') == 'ACTIVE' ? 'checked' : ''}} value="ACTIVE" name="status" type="radio" class="form-control" id="active">
                        <label for="active">Active</label>
                        <input {{Request::get('status') == 'INACTIVE' ? 'checked' : ''}} value="INACTIVE" name="status" type="radio" class="form-control" id="inactive">
                        <label for="inactive">Inactive</label>
                        <input type="submit" value="Filter" class="btn btn-primary">
                    </div>
                </div>                
            </form> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{route('users.create')}}" class="btn btn-primary">Create user</a>
        </div>
    </div>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><b>Name</b></th>
                <th><b>Username</b></th>
                <th><b>Email</b></th>
                <th><b>Avatar</b></th>
                <th><b>Status</b></th>
                <th><b>Action</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $u)
            <tr>
                <td>{{$u->name}}</td>
                <td>{{$u->username}}</td>
                <td>{{$u->email}}</td>
                <td>
                    @if($u->avatar)
                        <img src="{{ asset('storage/'.$u->avatar) }}" width="70px">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($u->status == "ACTIVE")
                        <span class="badge badge-success">
                            {{$u->status}}
                        </span>
                    @else
                        <span class="badge badge-danger">
                            {{$u->status}}
                        </span>
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
        <tfoot>
            <tr>
                <td colspan=10>
                    {{$user->appends(Request::all())->links()}}
                </td>
            </tr>
        </tfoot>
    </table>    
@endsection