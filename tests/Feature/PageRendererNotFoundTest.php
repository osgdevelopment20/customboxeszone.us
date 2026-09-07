<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * A missing page is an ordinary 404. It must not be written to the log as an
 * application error - that is what filled laravel.log with stack traces.
 */
class PageRendererNotFoundTest extends TestCase
{
    /** @test */
    public function an_unknown_slug_returns_404_without_logging_an_error(): void
    {
        Log::spy();

        $this->get('/definitely-not-a-real-slug-xyz')->assertNotFound();

        Log::shouldNotHaveReceived('error');
    }

    /** @test */
    public function stale_asset_urls_never_reach_the_page_renderer(): void
    {
        Log::spy();

        foreach ([
            '/assets/product_detail/699-590-5d2a7-printed-custom-double-wall-frame-tray.jpg',
            '/assets/product_detail/200-238-a3d6d-clock-box.jpg',
            '/storage/uploads/missing.png',
            '/img/missing.png',
            '/some-page/images/missing.jpg',
        ] as $url) {
            $this->get($url)->assertNotFound();
        }

        Log::shouldNotHaveReceived('error');
    }

    /**
     * The catch-all used to swallow /storage/{id}/{conversion}/{filename}, so
     * every media-library conversion image 404'd through the page renderer
     * instead of being served by the media.show route registered below it.
     *
     * @test
     */
    public function media_conversion_urls_reach_the_media_route_and_not_the_page_renderer(): void
    {
        $request = \Illuminate\Http\Request::create('/storage/12/thumb/example.jpg', 'GET');
        $route = app('router')->getRoutes()->match($request);

        $this->assertSame('media.show', $route->getName());
    }

    /** @test */
    public function a_two_segment_url_without_a_page_number_still_404s_quietly(): void
    {
        Log::spy();

        $this->get('/definitely-not-a-real-slug-xyz/some-prefix')->assertNotFound();

        Log::shouldNotHaveReceived('error');
    }
}
