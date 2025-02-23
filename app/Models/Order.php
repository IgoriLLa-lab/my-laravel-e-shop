<?php

namespace App\Models;

use App\Enum\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $casts = [
        'status' => OrderStatus::class,
    ];
    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('count')->withTimestamps();
    }

    /**
     * Calculate the total cost of the order.
     *
     * @return float|int
     */
    public function getTotalCost(): float|int
    {
        return $this->products->sum(function ($product) {
            return $product->price * ($product->pivot->count ?? 1);
        });
    }

    public function saveOrder(string $name, string $phone): bool
    {
        if ($this->status == OrderStatus::NEW) {
            $this->name = $name;
            $this->phone = $phone;
            $this->status = OrderStatus::PROCESSED;
            $this->save();
            session()->forget('orderId');

            return true;
        } else {
            return false;
        }
    }
}
