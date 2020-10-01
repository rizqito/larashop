@extends('layouts.global')
@section('title')Edit Category @endsection
@section('content')
<div class="col-md-8">
    @if(session('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
    @endif
    <form action="{{route('categories.update', $cat->id)}}" enctype="multipart/form-data" method="POST" class="bg-white shadow-sm p-3">
        @csrf
        @method('PATCH')
        <label>Category name</label> <br>
        <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}" value="{{old('name') ? old('name') : $cat->name}}" name="name">
        <div class="invalid-feedback">
            {{$errors->first('name')}}
        </div>
        <br><br>
        <label>Category slug</label>
        <input type="text" class="form-control {{$errors->first('slug') ? 'is-invalid' : ''}}" value="{{old('slug') ? old('slug') : $cat->slug}}" name="slug">
        <div class="invalid-feedback">
            {{$errors->first('slug')}}
        </div>
        <br><br>
        <label>Category image</label><br>
        @if($cat->image)
        <span>Current image</span><br>
        <img src="{{asset('storage/'. $cat->image)}}" width="120px">
        <br><br>
        @endif
        <input type="file" class="form-control {{$errors->first('image') ? 'is-invalid' : ''}}" name="image">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
        <div class="invalid-feedback">
            {{$errors->first('image')}}
        </div>
        <br><br>
        <input type="submit" class="btn btn-primary" value="Update">
    </form>
</div>
@endsection
