<?php

namespace App\Services;

use App\Models\KontenWebsite;

class WebsiteContent
{
    public function shared(string $locale): array
    {
        $request = request();
        $key = 'website.shared.'.$locale;
        if (! $request->attributes->has($key)) {
            $content = KontenWebsite::aktif()->where('judul', 'shared')
                ->whereHas('halaman', fn ($query) => $query->where('nama_halaman', 'home'))
                ->whereHas('bahasa', fn ($query) => $query->aktif()->where('kode_bahasa', $locale))
                ->firstOrFail();
            $request->attributes->set($key, $content->isi);
        }

        return $request->attributes->get($key);
    }
}
