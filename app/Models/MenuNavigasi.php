<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuNavigasi extends WebsiteModel
{
    protected $table = 'menu_navigasi';

    protected $primaryKey = 'id_menu';

    protected $casts = [];

    protected function integrityRules(): array
    {
        return [
            'id_halaman' => 'required|exists:halaman_website,id_halaman',
            'nama_menu' => 'required|string|max:100',
            'ikon' => 'nullable|string|max:50',
            'urutan' => 'required|integer|min:0',
        ];
    }

    public function halaman(): BelongsTo
    {
        return $this->belongsTo(HalamanWebsite::class, 'id_halaman');
    }
}
