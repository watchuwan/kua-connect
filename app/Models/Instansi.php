<?php

namespace App\Models;

use App\Enums\TipeInstansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Instansi extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $table = "master.instansi";
    protected $fillable = [
        "kode_instansi",
        "nama_instansi",
        "deskripsi_layanan",
        "kecamatan",
        "tipe",
        "aktif",
    ];

    protected function casts(): array
    {
        return [
            "tipe" => TipeInstansi::class,
            "aktif" => "boolean",
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom("nama_instansi")
            ->saveSlugsTo("slug");
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection("logo")
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion("thumb")
                    ->width(120)
                    ->height(120);
                $this->addMediaConversion("small")
                    ->width(320)
                    ->height(320);
            });
    }

    public function pelayanan(): HasMany
    {
        return $this->hasMany(Pelayanan::class, 'instansi_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl("logo", "thumb");
    }
}
