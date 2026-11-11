<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

/**
 * "All Products" browse screen - top level categories plus a filterable
 * grid of every published box, grown in steps with a "Load more" button.
 */
class AllProductsPage extends Component
{
    /** Products added to the grid by each "Load more" click. */
    const PER_LOAD = 24;

    public $search = '';

    /** How many products the grid currently shows. */
    public $limit = self::PER_LOAD;

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
        $this->resetLimit();
    }

    public function updatingCategoryId()
    {
        $this->resetLimit();
    }

    public function updatingSort()
    {
        $this->resetLimit();
    }

    public function selectCategory($categoryId)
    {
        $this->categoryId = (string) $categoryId;
        $this->resetLimit();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'categoryId', 'sort']);
        $this->resetLimit();
    }

    public function loadMore()
    {
        $this->limit = min((int) $this->limit + self::PER_LOAD, $this->total);
    }

    /**
     * Only loadMore() may grow the limit. Setting it from the browser would let
     * one request load every product (and its images) at once.
     */
    public function updatedLimit()
    {
        $this->resetLimit();
    }

    /** A new filter starts again from the first batch. */
    protected function resetLimit()
    {
        $this->limit = self::PER_LOAD;
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

    /**
     * Published products matching the current search, category and sort.
     */
    protected function filteredQuery()
    {
        $categoryIds = $this->categoryIdsForFilter();

        return Product::query()
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
            ->when($this->sort === 'latest', fn ($query) => $query->orderByDesc('id'));
    }

    public function getProductsProperty()
    {
        // The limit arrives from the browser, so keep it to whole batches.
        $limit = max(self::PER_LOAD, (int) ceil((int) $this->limit / self::PER_LOAD) * self::PER_LOAD);

        return $this->filteredQuery()
            ->with(['media', 'category'])
            ->limit($limit)
            ->get();
    }

    public function getTotalProperty()
    {
        return $this->filteredQuery()->count();
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
            'total' => $this->total,
            'selectedCategory' => $this->selectedCategory,
        ]);
    }
}
