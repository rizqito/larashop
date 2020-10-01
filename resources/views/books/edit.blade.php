@extends('layouts.global')
@section('footer-script')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $('#categories').select2({
        placeholder: "Pilih kategori ...",
        // minimumInputLength: 2,
        ajax: {
            url: '/ajax/categories/search',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    var categories = {!! $book->categories !!}

    categories.forEach(function(category){
        var option = new Option(category.name, category.id, true, true);
        $('#categories').append(option).trigger('change');
    });
</script>
@endsection
@section('title')Create Book @endsection
@section('content')
<div class="row">
    <div class="col-md-8">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('books.edit', $book->id) }}" method="post" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
            @method('PATCH')
            <label for="title">Title</label><br>
            <input type="text" class="form-control" type="text" name="title" placeholder="Book title" value="{{ $book->title }}">
            <br>
            <label for="cover">Cover</label>
            <small class="text-muted">Current Cover</small><br>
            @if($book->cover)
                <img src="{{ asset('storage/'.$book->cover) }}" width="96px">
            @endif
            <br><br>
            <input type="file" class="form-control" name="cover">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah cover</small>
            <br><br>
            <label for="slug">Slug</label><br>
            <input type="text" class="form-control" name="slug" value="{{ $book->slug }}" placeholder="enter-a-slug">
            <br>
            <label for="description">Description</label><br>
            <textarea name="description" id="description" class="form-control" placeholder="Give a description about this book">{{ $book->description }}</textarea>
            <br>
            <label for="categories">Categories</label><br>
            <select name="categories[]" multiple id="categories" class="form-control"></select>
            <br><br>
            <label for="stock">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" min="0" value="{{ $book->stock }}">
            <br>
            <label for="author">Author</label><br>
            <input type="text" class="form-control" name="author" id="author" placeholder="Book author" value="{{ $book->author }}">
            <br>
            <label for="publisher">Publisher</label><br>
            <input type="text" class="form-control" id="publisher" name="publisher" placeholder="Book publisher" value="{{ $book->publisher }}">
            <br>
            <label for="price">Price</label><br>
            <input type="number" name="price" id="price" class="form-control" placeholder="Book price" value="{{ $book->price }}">
            <br>
            <label for="">Status</label>
            <select class="form-control" name="status" id="status">
                <option {{ $book->status == 'PUBLISH' ? 'selected' : '' }} value="PUBLISH">PUBLISH</option>
                <option {{ $book->status == 'DRAFT' ? 'selected' : '' }} value="DRAFT">DRAFT</option>
            </select>
            <button class="btn btn-primary" value="Update">Update</button>
        </form>
    </div>
</div>
@endsection