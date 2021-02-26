@extends('layouts.app')

@section('title', '| Blog | Edit')
@section('content')
<div class="container mt-5">
    <h1 class="text-center">Edit Blog</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Edit Blog Form --}}
            @include('blog.form', [
                'method' => 'PATCH'
            ])
        </div>
    </div>
</div>

@endsection