@extends('layouts.app')

@section('title', '| Blog | Create')
@section('content')
<div class="container">
    <h1>Add Blog</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Add Blog Form --}}
            @include('blog.form', ['method' => 'POST'])
        </div>
    </div>
</div>
@endsection