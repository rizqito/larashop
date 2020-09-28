@extends('layouts.global')
@section('title') Categories list @endsection
@section('content')
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <form action="{{route('categories.index')}}">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Filter by category name" name="name">
                    <div class="input-group-append">
                        <input type="submit" value="filter" class="btn btn-primary">
                    </div>
                </div>
            </form> 
        </div>
        <div class="col-md-6">
            <ul class="nav nav-pills card-header-pills">
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link active">Published</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.trash') }}" class="nav-link">Trash</a>
                </li>
            </ul>
        </div>
    </div>
    <hr class="my-3">
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{route('categories.create')}}" class="btn btn-primary">Create category</a>
        </div>
    </div>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><b>Name</b></th>
                <th><b>Slug</b></th>
                <th><b>Image</b></th>
                <th><b>Action</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach($cat as $c)
            <tr>
                <td>{{$c->name}}</td>                
                <td>{{$c->slug}}</td>
                <td>
                    @if($c->image)
                        <img src="{{ asset('storage/'.$c->image) }}" width="70px">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <a href="{{ route('categories.edit',$c->id) }}" class="btn btn-info text-white btn-sm">Edit</a>
                    <form onsubmit="return confirm('Move category to trash?')" class="d-inline" action="{{ route('categories.destroy', $c->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                    <a href="{{ route('categories.show',$c->id) }}" class="btn btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=10>
                    {{$cat->appends(Request::all())->links()}}
                </td>
            </tr>
        </tfoot>
    </table>    
@endsection