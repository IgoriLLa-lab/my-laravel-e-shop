<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
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
        if ($this->status == 0) {
            $this->name = $name;
            $this->phone = $phone;
            $this->status = 1;
            $this->save();
            session()->forget('orderId');

            return true;
        } else {
            return false;
        }
    }
}
