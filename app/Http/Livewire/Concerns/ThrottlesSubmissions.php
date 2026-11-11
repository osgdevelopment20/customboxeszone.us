<?php

namespace App\Http\Livewire\Concerns;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Every public form submission writes a database row and sends an email, and
 * the forms have no captcha, so cap how often one visitor can submit each form.
 * Call after validate() so a form with mistakes does not use up attempts.
 */
trait ThrottlesSubmissions
{
    protected function throttleSubmission(string $errorField = 'email', int $maxAttempts = 5, int $decaySeconds = 600): void
    {
        $key = 'form-submit:' . static::class . ':' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);

            throw ValidationException::withMessages([
                $errorField => "Too many submissions. Please try again in {$minutes} minute(s), or call us.",
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);
    }
}
