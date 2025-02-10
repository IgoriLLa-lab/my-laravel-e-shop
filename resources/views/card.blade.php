<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <div class="labels">
            @if($product->isNew())
                <span class="badge badge-success">Новинка</span>
            @endif
            @if($product->isRecommended())
                <span class="badge badge-warning">Рекомендуем</span>
            @endif
            @if($product->isHit())
                <span class="badge badge-danger">Хит продаж!</span>
            @endif
        </div>
        <img src="{{\Illuminate\Support\Facades\Storage::url($product->image)}}" alt="">
        <div class="caption">
            <h3>{{ $product->name }}</h3>
            <p>{{ $product->price }} руб.</p>
            <p>Осталось штук: {{ $product->count }}</p>
            <p>
            <form action="{{ route('basket-add', $product) }}" method="post">
                @if($product->isAvailableForOrder())
                    <button type="submit" class="btn btn-primary" role="button">В корзину</button>
                @else
                    <span class="text-danger">Товар недоступен для заказа</span>
                @endif
                <a href="{{ route('product', [$product->category->code, $product->code]) }}" class="btn btn-default"
                   role="button">Подробнее</a>
                @csrf
            </form>
        </div>
    </div>
</div>
