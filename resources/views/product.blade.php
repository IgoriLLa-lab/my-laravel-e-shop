@extends('layouts.master')

@section('content')
    <h1>{{ $product->name }}</h1>
    <h1>{{ $product->category->name }}</h1>
    <p>Цена: <b>{{ $product->price }} руб.</b></p>
    <img src="{{\Illuminate\Support\Facades\Storage::url($product->image)}}" alt="">
    <p>{{ $product->description }}</p>
    @if($product->isAvailableForOrder())
        <form action="{{ route('basket-add', $product) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-success">Добавить в корзину</button>
        </form>
    @else
        <span class="text-danger">Товар недоступен для заказа</span>
        <br>
        <span>Сообщить мне о когда товар появиться:</span>
        <form method="post" action="{{ route('subscribe', $product) }}">
            @csrf
            <input type="text" name="email">
            <button type="submit">Отправить</button>
        </form>
    @endif
@endsection
