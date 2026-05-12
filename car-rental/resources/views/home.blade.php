@extends('layouts.app') {{-- use the base layout so we reuse shared HTML and styles --}}
@section('content') {{-- start the content section that fills the layout placeholder --}}
    @if (session('success')) {{-- check if a success message exists in the session --}}
        <p>{{ session('success') }}</p> {{-- display the success message to the user --}}
    @endif {{-- end the success check block --}}
    <h1>{{ $pageTitle }}</h1> {{-- print the title variable so users see the main heading --}}
    <p class="tagline">{{ $tagline }}</p> {{-- print the tagline variable to show a short message --}}
@endsection {{-- end the content section so the layout can render it --}}
