<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

/**
 * Livewire component for displaying products categorized by their categories on the homepage.
 *
 * This component fetches active categories that are set to display on the homepage,
 * along with their associated products, and renders them in a tabbed interface.
 */
class HomepageProductsWithCategory extends Component
{
    

    public function render(): \Illuminate\View\View
    {
        try {
          
            $productWithCategory = Category::active()
                    ->where('display_homepage', 1)
                    ->select(['id', 'name', 'slug', 'display_homepage'])
                    ->limit(3)
                    ->with(['products' => function ($query) {
                        $query->where('display_homepage', 1)
                            ->select([
                                'products.id',
                                'products.product_name',
                                'products.slug',
                                'products.display_homepage'
                            ])
                            ->orderBy('products.created_at', 'desc')
                            ->limit(12);
                    }])
                    ->get();

            return view('livewire.homepage-products-with-category', compact('productWithCategory'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error fetching homepage products with category: ' . $e->getMessage());

        }

    }
}
