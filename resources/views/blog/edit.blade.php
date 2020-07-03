@extends('layouts.app')

@section('title', '| Blog | Edit')
@section('content')
<div class="container">
    <div class="row">
        <h1>Edit Blog</h1>
    </div>
    <div class="row">
        {{-- Edit Blog Form --}}
        @include('blog.form', [
            'method' => 'PATCH'
        ])
    </div>
</div>

@endsection