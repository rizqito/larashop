@extends('layouts.global')
@section('title')Edit Category @endsection
@section('content')
<div class="col-md-8">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form action="{{ route('categories.update',$cat->id) }}" method="post" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
        @csrf
        @method('PATCH')
        <label>Category name</label>
        <input type="text" class="form-control" type="text" name="name" value="{{ $cat->name }}">
        <br><br>
        <label>Category slug</label>
        <input type="text" class="form-control" value="{{ $cat->slug }}" name="slug">
        <br><br>
        <label>Category Image</label><br>
        @if($cat->image)
        <span>Current image</span><br>
        <img src="{{ asset('storage/'. $cat->image) }}" width="120px">
        <br><br>
        @endif
        <input type="file" class="form-control" name="image">
        <small class="text-muted">Kosongkan jika tidan ingin mengubah gambar</small>
        <br><br>
        <input type="submit" class="btn btn-primary" value="Update">
    </form>
</div>
@endsection