<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description',
        'image',
        'price',
        'new',
        'hit',
        'recommended',
        'count'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Calculate the total cost of a product based on its price and count.
     *
     * @return float|int
     */
    public function getTotalCost(): float|int
    {
        return $this->price * ($this->pivot->count ?? 1);
    }

    /**
     * Check if the product is marked as "new".
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return (bool)$this->new;
    }

    /**
     * Check if the product is marked as a "hit".
     *
     * @return bool
     */
    public function isHit(): bool
    {
        return (bool)$this->hit;
    }

    /**
     * Check if the product is marked as "recommended".
     *
     * @return bool
     */
    public function isRecommended(): bool
    {
        return (bool)$this->recommended;
    }

    /**
     * Check if the product is available for order.
     *
     * @return bool
     */
    public function isAvailableForOrder(): bool
    {
        return $this->count > 0;
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }
}
