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
            <p class="ml-3">Author: {{ $blog->user_id }}</p>
        </div>

        <hr>
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{ $blog->content }}
            </div>
        </div>

        <hr>
        <div class="row">
            <h3>Comments 0</h3>
        </div>
        <hr>
    </div>
@endsection