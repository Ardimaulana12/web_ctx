<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Bahasa extends WebsiteModel
{
    protected $table = 'bahasa';

    protected $primaryKey = 'id_bahasa';

    protected $casts = ['is_default' => 'boolean'];

    protected function integrityRules(): array
    {
        return [
            'kode_bahasa' => 'required|string|max:10',
            'nama_bahasa' => 'required|string|max:50',
            'lokal_bahasa' => 'required|string|max:50',
            'is_default' => 'required|boolean',
        ];
    }

    public function konten(): HasMany
    {
        return $this->hasMany(KontenWebsite::class, 'id_bahasa');
    }

    public function kontak(): HasMany
    {
        return $this->hasMany(Kontak::class, 'id_bahasa');
    }
}
