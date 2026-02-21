<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    private array $supported = ['en', 'fr', 'rw'];
    private string $default = 'rw';

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->query('lang');
        if ($locale) {
            if (in_array($locale, $this->supported, true)) {
                $request->session()->put('locale', $locale);
            }
        }

        $locale = $request->session()->get('locale', $this->default);

        if (!in_array($locale, $this->supported, true)) {
            $locale = config('app.locale', $this->default);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
