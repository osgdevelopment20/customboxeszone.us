<div class="cbz-page">
    <div class="container">

        <div class="cbz-page__head">
            <div>
                <h1 class="cbz-page__title">All Products</h1>
                <p class="cbz-page__subtitle">
                    Browse our full packaging range, pick a box, set your size and quantity — then request a custom quote.
                    Final price, shipping, and production time are confirmed before payment.
                </p>
            </div>
        </div>

        {{-- Categories --}}
        <section class="cbz-section" aria-labelledby="cbz-categories-title">
            <div class="cbz-section__head">
                <h2 class="cbz-section__title" id="cbz-categories-title">Shop by Category</h2>
                @if ($selectedCategory)
                    <button type="button" class="cbz-link-btn" wire:click="clearFilters">
                        <i class="fi-rs-cross-small" aria-hidden="true"></i> Clear filter
                    </button>
                @endif
            </div>

            <div class="cbz-catgrid">
                @foreach ($categories as $category)
                    @php
                        $categoryMedia = $category->getFirstMedia();
                        $isActive = (string) $category->id === (string) $categoryId;
                    @endphp
                    <button type="button"
                            wire:click="selectCategory({{ $category->id }})"
                            wire:key="cat-{{ $category->id }}"
                            class="cbz-cat {{ $isActive ? 'is-active' : '' }}"
                            aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                        <span class="cbz-cat__media">
                            @if ($categoryMedia)
                                <img src="{{ $categoryMedia->getUrl('l') }}" alt="{{ $category->name }}"
                                     width="120" height="90" loading="lazy" decoding="async">
                            @else
                                <i class="fi-rs-box-alt" aria-hidden="true"></i>
                            @endif
                        </span>
                        <span class="cbz-cat__name">{{ $category->name }}</span>
                        <span class="cbz-cat__count">{{ $category->categories->count() }} {{ Str::plural('type', $category->categories->count()) }}</span>
                    </button>
                @endforeach
            </div>
        </section>

        {{-- Filter bar --}}
        <div class="cbz-filterbar">
            <div class="cbz-filterbar__search">
                <i class="fi-rs-search" aria-hidden="true"></i>
                <label for="cbz-search" class="cbz-sr-only">Search products</label>
                <input type="search" id="cbz-search" class="cbz-input"
                       wire:model.debounce.400ms="search" value="{{ $search }}" placeholder="Search boxes by name…">
            </div>

            <div class="cbz-filterbar__field">
                <label for="cbz-category" class="cbz-sr-only">Filter by category</label>
                <select id="cbz-category" class="cbz-input cbz-select" wire:model="categoryId">
                    <option value="" @selected($categoryId === '')>All categories</option>
                    @foreach ($categories as $category)
                        <optgroup label="{{ $category->name }}">
                            <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>All {{ $category->name }}</option>
                            @foreach ($category->categories as $subcategory)
                                <option value="{{ $subcategory->id }}" @selected((string) $categoryId === (string) $subcategory->id)>{{ $subcategory->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="cbz-filterbar__field">
                <label for="cbz-sort" class="cbz-sr-only">Sort products</label>
                <select id="cbz-sort" class="cbz-input cbz-select" wire:model="sort">
                    <option value="latest" @selected($sort === 'latest')>Newest first</option>
                    <option value="name_asc" @selected($sort === 'name_asc')>Name: A to Z</option>
                    <option value="name_desc" @selected($sort === 'name_desc')>Name: Z to A</option>
                </select>
            </div>
        </div>

        <div class="cbz-resultbar">
            <p wire:loading.remove wire:target="search,categoryId,sort,selectCategory,clearFilters,gotoPage,nextPage,previousPage">
                Showing <strong>{{ $products->count() }}</strong> of
                <strong>{{ number_format($products->total()) }}</strong> products
                @if ($selectedCategory) in <strong>{{ $selectedCategory->name }}</strong> @endif
            </p>
            <p wire:loading wire:target="search,categoryId,sort,selectCategory,clearFilters,gotoPage,nextPage,previousPage">
                <span class="cbz-spinner" aria-hidden="true"></span> Loading products…
            </p>
        </div>

        {{-- Products --}}
        @if ($products->total() === 0)
            <div class="cbz-empty">
                <div class="cbz-empty__icon" aria-hidden="true"><i class="fi-rs-search"></i></div>
                <h2 class="cbz-empty__title">No products found</h2>
                <p class="cbz-empty__text">Try a different search term or category.</p>
                <button type="button" class="cbz-btn cbz-btn--outline" wire:click="clearFilters">
                    Reset filters
                </button>
            </div>
        @else
            <div class="cbz-prodgrid">
                @foreach ($products as $product)
                    @php
                        $media = $product->getFirstMedia('product_gallery') ?? $product->getFirstMedia('product_front_image');
                        $productCategory = $product->category->first();
                    @endphp
                    <article class="cbz-prod" wire:key="prod-{{ $product->id }}">
                        <a href="{{ route('page', $product->slug) }}" class="cbz-prod__media"
                           title="{{ $product->product_title }}">
                            @if ($media)
                                <img src="{{ $media->getUrl('s') }}" alt="{{ $product->product_name }}"
                                     width="280" height="280" loading="lazy" decoding="async">
                            @else
                                <span class="cbz-prod__placeholder"><i class="fi-rs-box-alt" aria-hidden="true"></i></span>
                            @endif
                        </a>
                        <div class="cbz-prod__body">
                            @if ($productCategory)
                                <span class="cbz-chip">{{ $productCategory->name }}</span>
                            @endif
                            <h3 class="cbz-prod__name">
                                <a href="{{ route('page', $product->slug) }}">{{ $product->product_name }}</a>
                            </h3>
                            <a href="{{ route('page', $product->slug) }}" class="cbz-prod__cta">
                                Configure &amp; Quote <i class="fi-rs-arrow-small-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="cbz-pagination">
                {{ $products->onEachSide(1)->links() }}
            </div>
        @endif

    </div>
</div>
