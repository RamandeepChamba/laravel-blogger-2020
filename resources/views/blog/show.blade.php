@extends('layouts.app')

@section('title', '| Blog | Show')
@section('content')
    <div class="container">
        <div class="row">
            <h1>{{ $blog->title }}</h1>
        </div>

        <div class="row">
            <p class="ml-3 mt-2">Created_at: {{ date($blog->created_at) }}</p>
        </div>

        <div class="row">
            <p class="ml-3">Author: {{ $blog->user->name }}</p>
        </div>

        <hr>
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{ $blog->content }}
            </div>
        </div>

        <hr>
        <a href="#" class="btn btn-primary">Like</a><span class="ml-2">0</span>
        <hr>
        <div class="row">
            <h3>Comments {{$blog->comments->count()}}</h3>
        </div>
        <hr>
        {{-- Comment Form --}}
        <div class="row">
            @include('comment.form')
        </div>
        {{-- Comments --}}
        <hr>
        <div class="row">
            @include('comment.index', ['blog' => $blog])
        </div>
        <hr>
    </div>
@endsection