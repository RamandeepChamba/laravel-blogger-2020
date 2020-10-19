@extends('layouts.app')

@section('title', '| Home')
@section('content')
<div class="container">
    @isset($firstTime)
        <flash-message-component
            :flash-message="{{json_encode('Welcome to blogger!')}}"
            :flash-class="{{json_encode('success')}}"
        >
        </flash-message-component>
    @endisset
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="/blogs" class="display-4">Explore --></a>
        </div>
    </div>
</div>
@endsection
