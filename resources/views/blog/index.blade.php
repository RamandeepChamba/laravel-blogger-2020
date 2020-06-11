@extends('layouts.app')

@section('title', '| Blog | Explore')
@section('content')
<div class="container">
    <h1>Blogs</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Add Blog Form --}}
            <form action="/blog" method="POST">
                @csrf
                <div class="form-group">
                  <label for="blog-title">Title</label>
                  <input type="text" class="form-control" id="blog-title" 
                    min="5" name="title" required>
                </div>
                <div class="form-group">
                  <label for="blog-content">Content</label>
                  <input type="content" class="form-control" 
                    id="blog-content" name="content" required>
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
            @error('title')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror
            <br><br>

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
            
                            <div class="card-body">
                                <p>{{ $blog->content }}</p>
                            </div>
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