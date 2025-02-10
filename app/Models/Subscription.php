<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'email',
        'product_id'
    ];

    public function scopeActiveByProductID(Builder $query, int $productId) : Builder
    {
        return $query->where('active', 0)->where('product_id', $productId);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
