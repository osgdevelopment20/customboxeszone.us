<?php

namespace App\Providers;
use Livewire\Livewire;
use App\Http\Livewire\PageRenderer;
use App\Services\CartService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
     $this->app->singleton(CartService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Livewire::component('page-renderer', PageRenderer::class);
    }
}
