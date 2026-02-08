<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    private array $supported = ['en', 'fr', 'rw'];

    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, $this->supported, true)) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        return redirect()->back();
    }
}
