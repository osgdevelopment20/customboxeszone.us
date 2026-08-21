<?php

namespace App\Http\CacheProfiles;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

class HomepageCacheProfile extends CacheAllSuccessfulGetRequests
{
    /**
     * Gates both storing and serving cached responses.
     *
     * The header renders a live cart badge, so a visitor with items in their
     * cart must never be served (or have stored) a shared cached page - they
     * would see somebody else's item count.
     */
    public function enabled(Request $request): bool
    {
        if (! parent::enabled($request)) {
            return false;
        }

        return ! $this->hasCartItems($request);
    }

    public function shouldCacheRequest(Request $request): bool
    {
        if (Auth::check()) {
            return false;
        }

        if ($request->ajax()) {
            return false;
        }

        if ($this->isRunningInConsole()) {
            return false;
        }

        return $request->isMethod('get') && $request->path() === '/';
    }

    /**
     * Read the cart straight off the session so this stays cheap and does not
     * force a session start on visitors who have never added anything.
     */
    protected function hasCartItems(Request $request): bool
    {
        if (! $request->hasSession()) {
            return false;
        }

        return ! empty($request->session()->get(CartService::SESSION_KEY, []));
    }
}
