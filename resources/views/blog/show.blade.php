@extends('layouts.app')

@section('title', '| Blog | Show')
@section('content')
    <div class="container">
        
        @if (Session::has('message'))
            <flash-message-component
                :flash-message="{{json_encode(Session::get('message'))}}"
                :flash-class="{{json_encode(Session::get('flash-class'))}}"
            >
            </flash-message-component>
        @endif

        <div class="row">
            <h1>{{ $blog->title }}</h1>
        </div>

        <div class="row">
            <p class="ml-3 mt-2">Created_at: {{ date($blog->created_at) }}</p>
        </div>

        <div class="row">
            <p class="ml-3">
                Author: 
                <a href="{{ '/profiles/' . $blog->user->id }}">
                    {{ $blog->user->name }}
                </a>
                @auth
                    @if (auth()->user()->id === $blog->user_id)
                        (you)
                    @endif
                @endauth
            </p>
        </div>

        <hr>
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{ $blog->content }}
            </div>
        </div>

        <hr>
        <like-component 
            :auth-id="{{auth()->user() ? auth()->user()->id : -1}}"
            :blog-id="{{$blog->id}}"
            :likes-count="{{$blog->likes()->count()}}"
            :is-liked="{{$liked}}">
        </like-component>
        <hr>
        <comments-component
            :auth-id="{{auth()->user() ? auth()->user()->id : -1}}"
            :blog-id="{{$blog->id}}"
            :highlight-comment-node="{{$highlightCommentNode}}">
        </comments-component>
    </div>
@endsection