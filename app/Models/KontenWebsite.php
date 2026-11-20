<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KontenWebsite extends WebsiteModel
{
    protected $table = 'konten_website';

    protected $primaryKey = 'id_konten';

    protected $casts = ['isi' => 'array'];

    protected function integrityRules(): array
    {
        return [
            'id_halaman' => 'required|exists:halaman_website,id_halaman',
            'id_bahasa' => 'required|exists:bahasa,id_bahasa',
            'judul' => 'required|string|max:255',
            'isi' => 'required|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ];
    }

    public function halaman(): BelongsTo
    {
        return $this->belongsTo(HalamanWebsite::class, 'id_halaman');
    }

    public function bahasa(): BelongsTo
    {
        return $this->belongsTo(Bahasa::class, 'id_bahasa');
    }
}
