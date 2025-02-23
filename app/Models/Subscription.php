<?php

namespace App\Models;

use App\Mail\SendSubscriptionMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

class Subscription extends Model
{
    protected $fillable = [
        'email',
        'product_id'
    ];

    public function scopeActiveByProductId(Builder $query, int $productId): Builder
    {
        return $query->where('status', 0)->where('product_id', $productId);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function sendEmailBySubscription(Product $product): void
    {
        $subscriptions = self::activeByProductId($product->id)->get();

        foreach ($subscriptions as $subscription) {
            Mail::to($subscription->email)->send(new SendSubscriptionMessage($product));
            $subscription->status = 1;
            $subscription->save();
        }
    }
}
