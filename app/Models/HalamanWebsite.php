<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class HalamanWebsite extends WebsiteModel
{
    protected $table = 'halaman_website';

    protected $primaryKey = 'id_halaman';

    protected $casts = [];

    protected function integrityRules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:100', 'regex:~^/(?:[a-z0-9-]+)?$~'],
            'nama_halaman' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'urutan' => 'required|integer|min:0',
        ];
    }

    public function konten(): HasMany
    {
        return $this->hasMany(KontenWebsite::class, 'id_halaman');
    }

    public function menu(): HasMany
    {
        return $this->hasMany(MenuNavigasi::class, 'id_halaman');
    }
}
