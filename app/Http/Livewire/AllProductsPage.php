<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * "All Products" browse screen - top level categories plus a filterable,
 * paginated grid of every published box.
 */
class AllProductsPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    /** Selected category id - either a parent or one of its subcategories. */
    public $categoryId = '';

    public $sort = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => '', 'as' => 'category'],
        'sort' => ['except' => 'latest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function selectCategory($categoryId)
    {
        $this->categoryId = (string) $categoryId;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'categoryId', 'sort']);
        $this->resetPage();
    }

    /**
     * Top level categories, each with the subcategories used for filtering.
     */
    public function getCategoriesProperty()
    {
        return Cache::remember('all_products_categories', 3600, function () {
            return Category::with(['media', 'categories' => fn ($query) => $query->where('status', 1)])
                ->where('parent_id', 0)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * The selected category plus its children, so picking a parent shows
     * everything underneath it.
     */
    protected function categoryIdsForFilter(): array
    {
        if ($this->categoryId === '' || $this->categoryId === null) {
            return [];
        }

        $selected = (int) $this->categoryId;

        return Category::where('id', $selected)
            ->orWhere('parent_id', $selected)
            ->pluck('id')
            ->all();
    }

    public function getProductsProperty()
    {
        $categoryIds = $this->categoryIdsForFilter();

        return Product::query()
            ->with(['media', 'category'])
            ->where('status', 1)
            ->when(trim($this->search) !== '', function ($query) {
                $term = trim($this->search);
                $query->where(function ($inner) use ($term) {
                    $inner->where('product_name', 'like', "%{$term}%")
                        ->orWhere('product_title', 'like', "%{$term}%");
                });
            })
            ->when(! empty($categoryIds), function ($query) use ($categoryIds) {
                $query->whereHas('category', fn ($inner) => $inner->whereIn('categories.id', $categoryIds));
            })
            ->when($this->sort === 'name_asc', fn ($query) => $query->orderBy('product_name'))
            ->when($this->sort === 'name_desc', fn ($query) => $query->orderByDesc('product_name'))
            ->when($this->sort === 'latest', fn ($query) => $query->orderByDesc('id'))
            ->paginate(24);
    }

    public function getSelectedCategoryProperty()
    {
        return $this->categoryId === '' ? null : Category::find($this->categoryId);
    }

    public function render()
    {
        return view('livewire.all-products-page', [
            'categories' => $this->categories,
            'products' => $this->products,
            'selectedCategory' => $this->selectedCategory,
        ]);
    }
}
