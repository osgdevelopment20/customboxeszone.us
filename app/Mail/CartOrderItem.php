<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_order_id',
        'product_id',
        'product_name',
        'product_title',
        'product_slug',
        'category',
        'image',
        'length',
        'width',
        'height',
        'units',
        'quantity',
        'artwork',
        'notes',
    ];

    protected $casts = [
        'artwork' => 'array',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(CartOrder::class, 'cart_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * "10 x 8 x 4 inches" - empty when no dimensions were provided.
     */
    public function getDimensionsAttribute(): ?string
    {
        $parts = array_filter([$this->length, $this->width, $this->height]);

        if (empty($parts)) {
            return null;
        }

        return implode(' x ', $parts) . ($this->units ? ' ' . $this->units : '');
    }
}
