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
                    <li class="list-group-item">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ $blog->title }}</h3>
                                <br>
                                <cite>~{{ $blog->user_id }}</cite>
                            </div>
            {{-- 
                            <div class="card-body">
                                <p>{{ $blog->content }}</p>
                            </div>
            --}}
                            <form action="/blogs/{{ $blog->id }}/edit" method="GET">
                                @csrf
                                <button type="submit">Edit</button>
                            </form>
                            <a href="/blogs/{{ $blog->id }}">view</a>
                            <form action="/blogs/{{ $blog->id }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit">Delete</button>
                            </form>
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