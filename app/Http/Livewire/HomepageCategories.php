<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class HomepageCategories extends Component
{

  

    public function render(): \Illuminate\View\View
    {
        $homepageSubcategories =  Category::active()
                ->where('homepage_category_banner', 1)
                ->select(['id', 'name', 'slug'])
                ->orderBy('name')
                ->get();
      
        return view('livewire.homepage-categories', compact('homepageSubcategories'));
    }
}
