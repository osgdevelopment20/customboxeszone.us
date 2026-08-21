<?php

namespace App\View\Components;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Menu extends Component
{
    public $byIndustries;
    public $byStyles;
    public $byMaterials;

    public function __construct()
    {
        // Cache all menu data for 24 hours (86400 seconds)
        $menuData = Category::where('parent_id', 0)
                ->active()
                ->with(['categories' => function ($query) {
                    $query->select('id', 'parent_id', 'name', 'slug', 'by_industry', 'by_style', 'by_material')
                        ->where(function ($q) {
                            $q->where('by_industry', 1)
                                ->orWhere('by_style', 1)
                                ->orWhere('by_material', 1);
                        });
                }])
                ->select('id', 'name', 'slug', 'by_industry', 'by_style', 'by_material')
                ->get();

        // Filter the cached results for each category type
        $this->byIndustries = $menuData->filter(function ($item) {
            return $item->by_industry == 1;
        });

        $this->byStyles = $menuData->filter(function ($item) {
            return $item->by_style == 1;
        });

        $this->byMaterials = $menuData->filter(function ($item) {
            return $item->by_material == 1;
        });
    }

    public function render()
    {
        return view('components.menu');
    }
}
