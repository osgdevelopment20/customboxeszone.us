<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class BreadcrumbService
{
    private array $breadcrumbs = [];

    public function generateCategoryBreadcrumb(Category $category)
    {
        return $this->buildBreadcrumb([
            ['text' => 'Home', 'url' => route('index'), 'active' => false],
            ['text' => 'Box By Industry', 'url' => route('allcategories'), 'active' => false],
            ['text' => $category->name, 'url' => '#', 'active' => true]
        ]);
    }

    public function generateProductBreadcrumb($product)
    {

        $main_category = Category::where('id', $product->category[0]->parent_id)->first();
        return $this->buildBreadcrumb([
            ['text' => 'Home', 'url' => route('index'), 'active' => false],
            ['text' => 'Box By Industry', 'url' => route('allcategories'), 'active' => false],
            ['text' => $main_category->name, 'url' => route('page', $main_category->slug), 'active' => false],
            ['text' => $product->category[0]->name, 'url' => route('page', $product->category[0]->slug), 'active' => false],
            ['text' => $product->product_name, 'url' => route('page', $product->slug), 'active' => true],
        ]);
    }

    private function buildBreadcrumb(array $items)
    {
        return collect($items)->map(function ($item) {
            $color = $item['active'] ? '#757474' : '#E62A16';
            return "<a style='color:{$color};' href='{$item['url']}'>{$item['text']}</a>";
        })->implode(' / ');
    }
}
