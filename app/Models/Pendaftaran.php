<?php

namespace App\Models;

use App\Enums\StatusPendaftaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Pendaftaran extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $table = 'pelayanan.pendaftaran';
    protected $fillable = ['nomor_antrean', 'pelayanan_id', 'data', 'status', 'waktu_dilayani', 'waktu_selesai'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'status' => StatusPendaftaran::class,
            'waktu_dilayani' => 'datetime',
            'waktu_selesai' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nomor_antrean')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection('pendaftaran_files');
    }

    public function pelayanan(): BelongsTo
    {
        return $this->belongsTo(Pelayanan::class, 'pelayanan_id');
    }
}
