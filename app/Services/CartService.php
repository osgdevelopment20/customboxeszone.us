<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Session backed cart for custom box enquiries.
 *
 * There is no pricing in this business model - every box is quoted manually -
 * so the cart only carries the specification the customer configured on the
 * product page. Each row keeps its own dimensions, quantity and artwork.
 */
class CartService
{
    public const SESSION_KEY = 'cbz_cart';

    /**
     * All rows currently in the cart.
     */
    public function items(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    /**
     * Number of rows in the cart (not the sum of quantities).
     */
    public function count(): int
    {
        return count($this->items());
    }

    /**
     * Sum of every row quantity - used for the header badge tooltip.
     */
    public function totalQuantity(): int
    {
        return (int) collect($this->items())->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function get(string $rowId): ?array
    {
        return Arr::get($this->items(), $rowId);
    }

    /**
     * Add a configured product to the cart.
     *
     * Identical specifications are merged into a single row so the customer
     * does not end up with duplicates after a double submit.
     *
     * @return string The row id of the added / merged row.
     */
    public function add(Product $product, array $attributes): string
    {
        $row = $this->makeRow($product, $attributes);
        $items = $this->items();

        if (isset($items[$row['row_id']])) {
            $items[$row['row_id']]['quantity'] += $row['quantity'];
            $items[$row['row_id']]['artwork'] = array_values(array_unique(array_merge(
                $items[$row['row_id']]['artwork'] ?? [],
                $row['artwork']
            )));
        } else {
            $items[$row['row_id']] = $row;
        }

        $this->persist($items);

        return $row['row_id'];
    }

    /**
     * Update the quantity of a single row. A quantity below one removes it.
     */
    public function updateQuantity(string $rowId, int $quantity): void
    {
        $items = $this->items();

        if (! isset($items[$rowId])) {
            return;
        }

        if ($quantity < 1) {
            $this->remove($rowId);

            return;
        }

        $items[$rowId]['quantity'] = $quantity;

        $this->persist($items);
    }

    public function remove(string $rowId): void
    {
        $items = $this->items();

        unset($items[$rowId]);

        $this->persist($items);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Contact details captured on the product page, used to prefill checkout.
     */
    public function contactDetails(): array
    {
        foreach ($this->items() as $item) {
            if (! empty($item['email'])) {
                return [
                    'name' => $item['name'] ?? null,
                    'email' => $item['email'] ?? null,
                    'phone' => $item['phone'] ?? null,
                ];
            }
        }

        return ['name' => null, 'email' => null, 'phone' => null];
    }

    /**
     * Build a normalised cart row from a product plus submitted attributes.
     */
    protected function makeRow(Product $product, array $attributes): array
    {
        $quantity = max(1, (int) ($attributes['quantity'] ?? 1));
        $artwork = array_values(array_filter((array) ($attributes['artwork'] ?? [])));

        $specification = [
            'length' => $this->cleanValue($attributes['length'] ?? null),
            'width' => $this->cleanValue($attributes['width'] ?? null),
            'height' => $this->cleanValue($attributes['height'] ?? null),
            'units' => $this->cleanValue($attributes['units'] ?? null),
        ];

        return array_merge($specification, [
            'row_id' => $this->rowId($product->id, $specification),
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_title' => $product->product_title,
            'product_slug' => $product->slug,
            'category' => $this->resolveCategory($product),
            'image' => $this->resolveImage($product),
            'name' => $this->cleanValue($attributes['name'] ?? null),
            'email' => $this->cleanValue($attributes['email'] ?? null),
            'phone' => $this->cleanValue($attributes['phone'] ?? null),
            'notes' => $this->cleanValue($attributes['notes'] ?? null),
            'quantity' => $quantity,
            'artwork' => $artwork,
            'added_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Deterministic id so the same box in the same size merges instead of
     * stacking up as separate rows.
     */
    protected function rowId(int $productId, array $specification): string
    {
        return substr(md5($productId . '|' . implode('|', array_map(
            fn ($value) => Str::lower((string) $value),
            $specification
        ))), 0, 16);
    }

    protected function resolveCategory(Product $product): ?string
    {
        $category = $product->relationLoaded('category')
            ? $product->category->first()
            : $product->category()->first();

        return $category->name ?? null;
    }

    protected function resolveImage(Product $product): ?string
    {
        $media = $product->getFirstMedia('product_gallery')
            ?? $product->getFirstMedia('product_front_image');

        return $media?->getUrl('s');
    }

    protected function cleanValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    protected function persist(array $items): void
    {
        session()->put(self::SESSION_KEY, $items);
    }
}
