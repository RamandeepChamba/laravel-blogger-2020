@extends('layouts.app')

@section('content')
    <h2>Hello</h2>
    <ul>
        @forelse ($users as $user)
            <li>{{ $user }}</li>
        @empty
            <h3>No users here</h3>
        @endforelse
    </ul>
@endsection