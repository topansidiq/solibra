@extends('admin.layouts.app')

@section('content')
    // resources/views/books/show.blade.php
    <h1>{{ $book->title }}</h1>
    <p>Penulis: {{ $book->author }}</p>
    <p>ISBN: {{ $book->isbn }}</p>
@endsection
