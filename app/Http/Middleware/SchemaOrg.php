<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class SchemaOrg
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $schemaWebsite = Schema::webSite()
            ->name(config('app.name'))
            ->identifier(route('index')."/#website")
            ->url(route('index'));
        $sameAs = [];
        if (sys_settings('facebook'))
            $sameAs[] = sys_settings('facebook');
        if (sys_settings('x'))
            $sameAs[] = sys_settings('x');
        if (sys_settings('instagram'))
            $sameAs[] = sys_settings('instagram');
        if (sys_settings('youtube'))
            $sameAs[] = sys_settings('youtube');

        $schemaOrganization = Schema::Organization()
            ->name(config('app.name'))
            ->email(sys_settings('email'))
            ->telephone(sys_settings('phone'))
            ->url(route('index'))
            ->description(sys_settings('site_meta_description'))
            ->contactPoint(
                Schema::contactPoint()
                    ->telephone(sys_settings('phone'))
                    ->contactType('customer service')
                    ->email(sys_settings('email'))
                    ->areaServed([
                        'Us'
                    ])
                    ->availableLanguage([
                        'English'
                    ])
            )
            ->sameAs($sameAs)
            ->address(
                Schema::postalAddress()
                    ->addressLocality('East Meadow')
                    ->addressRegion('NY')
                    ->addressCountry('USA')
                    ->postalCode('11554')
                    ->streetAddress(sys_settings('address'))
            )
            ->aggregateRating(
                Schema::aggregateRating()
                    ->ratingValue(5)
                    ->ratingCount(37)
                    ->bestRating(5)
            );

        // Local Business Schema
        $schemaBusiness = Schema::store()
            ->name(config('app.name'))
            ->image(asset('logo.svg'))
            ->url(route('index'))
            ->telephone(sys_settings('phone'))
            ->email(sys_settings('email'))
            ->description('Professional custom box packaging solutions provider offering high-quality packaging materials, custom box manufacturing services, and innovative packaging solutions for businesses.')
            ->logo(
                Schema::imageObject()
                    ->url(asset('logo.svg'))
                    ->width(300)
                    ->height(100)
                    ->caption(config('app.name') . ' Logo')
            )
            ->address(
                Schema::postalAddress()
                    ->addressLocality('East Meadow')
                    ->addressRegion('NY')
                    ->addressCountry('USA')
                    ->postalCode('11554')
                    ->streetAddress(sys_settings('address'))
            )
            ->geo(
                Schema::geoCoordinates()
                    ->latitude(40.6317823)
                    ->longitude(-73.9663371)
            )
            ->paymentAccepted([
                'Credit Card',
                'Debit Card',
                'Bank Transfer',
                'Wire Transfer'
            ])
            ->currenciesAccepted(['USD'])
            ->priceRange('$$ - $$$')
            ->additionalType([
                'https://schema.org/Store',
                'https://schema.org/LocalBusiness',
                'https://schema.org/ProfessionalService',
                'https://schema.org/ManufacturingBusiness',
                'https://schema.org/WholesaleStore'
            ])
            ->openingHoursSpecification([
                Schema::openingHoursSpecification()
                    ->dayOfWeek(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])
                    ->opens('09:00')
                    ->closes('18:00'),
                Schema::openingHoursSpecification()
                    ->dayOfWeek(['Saturday'])
                    ->opens('10:00')
                    ->closes('16:00')
            ])
            ->sameAs($sameAs)
            ->areaServed(['United States'])
            ->hasOfferCatalog(
                Schema::offerCatalog()
                    ->name('Custom Packaging Solutions')
                    ->itemListElement([
                        Schema::offer()->name('Custom Boxes'),
                        Schema::offer()->name('Retail Packaging'),
                        Schema::offer()->name('Shipping Boxes'),
                        Schema::offer()->name('Product Packaging'),
                        Schema::offer()->name('Custom Printed Boxes')
                    ])
            );



        \View::share(['schemaWebsite' => $schemaWebsite, 'schemaOrganization' => $schemaOrganization,'schemaBusiness' => $schemaBusiness]);

        return $next($request);
    }
}
