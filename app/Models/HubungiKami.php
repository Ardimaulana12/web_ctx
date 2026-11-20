<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HubungiKami extends Model
{
    protected $table = 'hubungi_kami';

    protected $primaryKey = 'id_hubungi';

    protected $fillable = ['nama', 'email', 'perusahaan', 'subjek', 'pesan'];
}
