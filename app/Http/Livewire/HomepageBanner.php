<?php

namespace App\Http\Livewire;

use App\Models\Banner;
use Livewire\Component;

class HomepageBanner extends Component
{
    public function render()
    {
        $banners = cache()->remember('homepage-banner', 3600, function () {
            return Banner::active()->latest()->get();
        });
        return view('livewire.homepage-banner',compact('banners'));
    }
}
