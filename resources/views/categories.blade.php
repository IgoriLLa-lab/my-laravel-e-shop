@extends('layouts.master')

@section('content')
        @foreach($categories as $category)
            <div class="panel">
                <a href="{{ route('category', $category->code) }}">
                    <img src="http://laravel-diplom-1.rdavydov.ru/storage/categories/portable.jpg">
                    <h2>{{$category->name}}</h2>
                </a>
                <p>
                    {{$category->description}}
                </p>
            </div>
        @endforeach
@endsection
