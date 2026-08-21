<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'quantity',
        'product_id',
        'variation_id',
        'referrer_page',
    ];

    /**
     * Get the product that owns the variation quote.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variation that owns the quote.
     */
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
}
