<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

final class LanguageController extends Controller
{
    //
    public function swap($locale): \Illuminate\Http\RedirectResponse
    {
        // available language in template array
        $availLocale = ['en' => 'en', 'fr' => 'fr','de' => 'de','pt' => 'pt'];
        // check for existing language
        if (array_key_exists($locale, $availLocale)) {
            session()->put('locale', $locale);
        }
        return redirect()->back();
    }
}
