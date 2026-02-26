<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

final class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public static $mainLanguage = 'en';

    public static $languages      = ['en', 'uk', 'de', 'pl'];
    public static $languageTitles = ['EN', 'UA', 'DE', 'PL'];

    public static function getLocale(): string|null
    {
        $path = request()->getPathInfo();
        $segments = explode('/', trim($path, '/'));

        if (!empty($segments[0]) && in_array($segments[0], self::$languages)) {
            return $segments[0];
        }

        return null;
    }

    public function handle($request, Closure $next)
    {
        $locale = self::getLocale();

        if ($locale) {
            if ($locale == self::$mainLanguage) {
                // Створюємо URL без префікса мови
                $path = $request->getPathInfo();
                $newPath = preg_replace('/^\/' . $locale . '(?=\/|$)/', '', $path) ?: '/';

                return redirect($newPath, 301);
            }
            App::setLocale($locale);
        } else {
            App::setLocale(self::$mainLanguage);
        }

        return $next($request);
    }
}
