@extends('layouts.app')

@section('title', '| Followers')
@section('content')
<div class="container">
    <h1>
        @isset($followers)
            Followers
        @endisset

        @isset($followings)
            Followings
        @endisset
    </h1>
    <hr>
    <div>
        @isset($followers)
            @if (count($followers))
                @include('follower.list', ['users' => $followers])
            @else
                {{-- No Followers --}}
                <div class="alert alert-primary" role="alert">
                    No followers :(
                </div>
            @endif
        @endisset

        @isset($followings)
            @if (count($followings))
                @include('follower.list', ['users' => $followings])          
            @else
                {{-- No Followings --}}
                <div class="alert alert-primary" role="alert">
                    You are following no one :(
                </div>
            @endif
        @endisset
    </div>
</div>
@endsection