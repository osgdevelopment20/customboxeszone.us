<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'name',
        'email',
        'phone',
        'notes',
        'items_count',
        'total_quantity',
        'status',
        'source',
        'ip_address',
        'city',
        'region',
        'country',
        'postal',
    ];

    protected $casts = [
        'items_count' => 'integer',
        'total_quantity' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(CartOrderItem::class);
    }

    /**
     * Human friendly, non guessable reference shown to the customer.
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'CBZ-' . now()->format('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
