<?php

namespace App\Http\Livewire\Concerns;

/**
 * Receives the ipinfo.io lookup performed in the browser so submissions can be
 * attributed to a region. Mirrors the listener used by the older quote forms.
 */
trait DetectsVisitorLocation
{
    public $ip_address;
    public $city;
    public $region;
    public $country;
    public $postal;

    public function locationDetected($data)
    {
        $this->ip_address = $data['ip'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->region = $data['region'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->postal = $data['postal'] ?? null;
    }

    protected function locationPayload(): array
    {
        return [
            'ip_address' => $this->ip_address,
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
            'postal' => $this->postal,
        ];
    }
}
