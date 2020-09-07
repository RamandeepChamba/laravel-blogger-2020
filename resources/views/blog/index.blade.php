@extends('layouts.app')

@section('title', '| Blog | Explore')
@section('content')
<div class="container">
    <h1>Blogs</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Blogs list --}}
            <ul id="blogs" class="list-group">
                @forelse ($blogs as $blog)
                    <li class="list-group-item mt-5">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ $blog->title }}</h3>
                                <br>
                                <cite>~{{ $blog->user->name }}</cite>
                            </div>
            {{-- 
                            <div class="card-body">
                                <p>{{ $blog->content }}</p>
                            </div>
            --}}    
                            <p class="mb-0">
                                <a href="/blogs/{{ $blog->id }}" 
                                    class="btn btn-primary w-auto">View</a>
                            </p>
                            @auth
                            @if(auth()->user()->id === $blog->user_id)
                            <form action="/blogs/{{ $blog->id }}/edit" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-warning w-auto">Edit</button>
                            </form>
                            <form action="/blogs/{{ $blog->id }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger w-auto">Delete</button>
                            </form>
                            @endif
                            @endauth
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">
                        <div class="alert alert-primary" role="alert">
                            No blogs here
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection