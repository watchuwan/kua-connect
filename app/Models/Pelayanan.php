<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Pelayanan extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $table = 'master.pelayanan';
    protected $fillable = ['nama_pelayanan', 'instansi_id', 'aktif'];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nama_pelayanan')
            ->saveSlugsTo('slug');
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'pelayanan_id');
    }

    public function formFields(): HasMany
    {
        return $this->hasMany(FormField::class, 'pelayanan_id');
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection("icon")
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion("thumb")
                    ->width(80)
                    ->height(80);
                $this->addMediaConversion("small")
                    ->width(256)
                    ->height(256);
            });
    }

    public function getIconUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl("icon", "thumb");
    }
}
