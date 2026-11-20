<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class WebsiteModel extends Model
{
    protected $guarded = [];

    abstract protected function integrityRules(): array;

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            Validator::make($model->attributesToArray(), $model->integrityRules() + [
                'status' => ['required', 'in:aktif,nonaktif'],
            ])->validate();
            if ($model instanceof KontenWebsite && strlen($model->getAttributes()['isi']) > 65535) {
                throw ValidationException::withMessages(['isi' => 'Content exceeds the ERD TEXT capacity (65535 bytes).']);
            }
            if ($model instanceof Bahasa && $model->is_default &&
                ($model->status !== 'aktif' || Bahasa::where('is_default', true)->whereKeyNot($model->getKey() ?? 0)->exists())) {
                throw ValidationException::withMessages(['is_default' => 'Only one active default language is allowed.']);
            }
        });
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
