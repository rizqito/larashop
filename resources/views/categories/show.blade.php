@extends('layouts.global')
@section('title') Detail category @endsection
@section('content')
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <label><b>Category name:</b></label><br>
                {{ $cat->name }}<br><br>
                <label><b>Category slug:</b></label><br>
                {{ $cat->slug }}<br><br>
                <label><b>Category image</b></label><br>
                @if($cat->image)
                    <img src="{{ asset('storage/'.$cat->image) }}" width="128px">
                @endif                
            </div>
        </div>
    </div>
@endsection