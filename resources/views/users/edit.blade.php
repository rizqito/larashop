@extends('layouts.global')
@section('title') Edit User @endsection
@section('content')
<div class="col-md-8">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
        @csrf
        @method('PATCH')
        <label for="name">Name</label>
        <input type="text" class="form-control" placeholder="Full Name" type="text" name="name" id="name" value=" {{$user->name}} ">
        <br>
        <label for="username">Username</label>
        <input type="text" class="form-control" placeholder="username" type="text" name="username" id="username" value=" {{$user->username}} " readonly>
        <br>
        <label for="">Status</label>
        <input type="radio" class="form-control" value="ACTIVE" id="active" name="status" {{$user->status=='ACTIVE' ? 'checked' : ''}}>
        <label for="active">Active</label>
        <input type="radio" class="form-control" value="INACTIVE" id="inactive" name="status" {{$user->status=='INACTIVE' ? 'checked' : ''}}>
        <label for="inactive">Inactive</label>
        <br><br>
        <label for="">Roles</label>
        <br>
        <input type="checkbox" {{in_array("ADMIN", json_decode($user->roles)) ? "checked" : ''}} name="roles[]" id="ADMIN" value="ADMIN">
        <label for="ADMIN">Administrator</label>
        <input type="checkbox" {{in_array("STAFF", json_decode($user->roles)) ? "checked" : ""}} name="roles[]" id="STAFF" value="STAFF">
        <label for="STAFF">Staff</label>
        <input type="checkbox" {{in_array("CUSTOMER", json_decode($user->roles)) ? 'checked' : ''}} name="roles[]" id="CUSTOMER" value="CUSTOMER">
        <label for="CUSTOMER">Customer</label>
        <br><br>
        <label for="phone">Phone Number</label>
        <br>
        <input type="text" name="phone" class="form-control" value=" {{$user->phone}} ">
        <br>
        <label for="address">Address</label>
        <textarea name="address" id="address" class="form-control"> {{$user->address}} </textarea>
        <br>
        <label for="avatar">Avatar Image</label>
        <br>
        Current avatar : <br>
        @if($user->avatar)
            <img src="{{asset('storage/'.$user->avatar)}}" width="120px">
            <br>
        @else
            No avatar
        @endif
        <br>
        <input type="file" id="avatar" name="avatar" class="form-control">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah avatar</small>
        <hr class="my-3">
        <label for="email">Email</label>
        <input type="email" class="form-control" placeholder="user@mail.com" name="email" id="email" value="{{ $user->email }}" readonly>
        <br>        
        <input type="submit" class="btn btn-primary" value="Save">
    </form>
</div>
@endsection