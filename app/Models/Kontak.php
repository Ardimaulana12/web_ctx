<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kontak extends WebsiteModel
{
    protected $table = 'kontak';

    protected $primaryKey = 'id_kontak';

    protected $casts = [];

    protected function integrityRules(): array
    {
        return [
            'id_bahasa' => 'required|exists:bahasa,id_bahasa',
            'alamat' => 'required|string',
            'email' => 'required|email|max:100',
            'telepon' => ['required', 'string', 'max:50', 'regex:/^[0-9+() .-]+$/'],
            'peta_lokasi' => 'nullable|url:http,https|max:255',
            'jam_operasional' => 'nullable|string|max:100',
        ];
    }

    public function bahasa(): BelongsTo
    {
        return $this->belongsTo(Bahasa::class, 'id_bahasa');
    }
}
