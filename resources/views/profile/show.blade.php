@extends('layouts.app')

@section('title', '| Profile')
@section('content')
<div class="container">
    <profile-component
        :a-profile="{{$profile}}"
        :auth-id="{{auth()->user() ? auth()->user()->id : -1}}"
    >
    </profile-component>
</div>
@endsection