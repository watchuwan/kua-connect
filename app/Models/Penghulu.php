<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penghulu extends Model
{
    protected $table = 'master.penghulu';

    protected $fillable = ['nama', 'nip', 'no_hp', 'aktif'];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'penghulu_id');
    }
}
