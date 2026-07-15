<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    private const SUPPORTED_LOCALES = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('locale') && in_array($request->query('locale'), self::SUPPORTED_LOCALES, true)) {
            $locale = $request->query('locale');

            Session::put('locale', $locale);

            if (auth()->check()) {
                auth()->user()->update(['preferred_language' => $locale]);
            }

            return redirect($request->url());
        }

        $locale = Session::get('locale')
            ?? auth()->user()?->preferred_language
            ?? config('app.locale', 'ar');

        if (!in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
