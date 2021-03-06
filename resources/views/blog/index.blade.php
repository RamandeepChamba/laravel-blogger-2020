@extends('layouts.app')

@section('title', '| Blog | Explore')
@section('content')
<div class="container">
    @if (Session::has('message'))
    <flash-message-component
        :flash-message="{{json_encode(Session::get('message'))}}"
        :flash-class="{{json_encode(Session::get('flash-class'))}}"
    >
    </flash-message-component>
    @endif
    <h1>Blogs</h1>
    @php
        $url = url()->current();
        $urlPath = parse_url($url)['path']
    @endphp
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Filters --}}
            <blogs-filter-component
                :auth-id="{{auth()->user() ? auth()->user()->id : -1}}"
                :blog-filters="{{json_encode($filters ?? NULL)}}"
                :url-path="{{json_encode($urlPath)}}"
            >
            </blogs-filter-component>
            {{-- Blogs list --}}
            <ul id="blogs" class="list-group">
                @forelse ($blogs as $blog)
                    <li class="list-group-item mt-5">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ $blog->title }}</h3>
                                <br>
                                <a href="{{ '/profiles/' . $blog->user->id }}">
                                    ~{{ $blog->user->name }}
                                </a>
                                @auth
                                    @if (auth()->user()->id === $blog->user_id)
                                        (you)
                                    @endif
                                @endauth
                            </div>
                            <p class="mb-0">
                                <a href="/blogs/{{ $blog->id }}" >
                                    <button
                                        class="btn btn-primary w-auto"
                                        onclick="this.disabled=true;">
                                        View
                                    </button>
                                </a>
                            </p>
                            @auth
                            @if(auth()->user()->id === $blog->user_id)
                            <form action="/blogs/{{ $blog->id }}/edit" method="GET" class="disableButtonForm">
                                @csrf
                                <button type="submit" class="btn btn-warning w-auto">Edit</button>
                            </form>
                            <form action="/blogs/{{ $blog->id }}" method="POST" class="disableButtonForm">
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
            {{-- Pagination --}}
            {{isset($filters) ? $blogs->withQueryString()->links() : null}}
        </div>
    </div>
</div>
@endsection