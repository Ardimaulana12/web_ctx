<?php

namespace App\Http\Middleware;

use App\Models\Bahasa;
use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $languages = Bahasa::aktif()->orderByDesc('is_default')->orderBy('id_bahasa')->get();
        $default = $languages->firstWhere('is_default', true);
        abort_unless($default, 503, 'Website default language has not been configured.');
        $language = $languages->firstWhere('kode_bahasa', $request->session()->get('locale')) ?? $default;
        $request->attributes->set('website.languages', $languages);
        $request->attributes->set('website.language', $language);
        app()->setLocale($language->kode_bahasa);
        app('translator')->setFallback($default->kode_bahasa);
        app('translator')->setLoaded([]);

        return $next($request);
    }
}
