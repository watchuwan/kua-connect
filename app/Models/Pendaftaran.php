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

    protected static function booted(): void
    {
        static::created(function (Pendaftaran $pendaftaran) {
            $pendaftaran->logActivity('created', 'Pendaftaran baru dengan nomor antrean ' . $pendaftaran->nomor_antrean);
        });

        static::updated(function (Pendaftaran $pendaftaran) {
            if ($pendaftaran->isDirty('status')) {
                $from = $pendaftaran->getOriginal('status')->value ?? $pendaftaran->getOriginal('status');
                $to = $pendaftaran->status->value;
                $pendaftaran->logActivity('status_updated', "Status berubah dari {$from} menjadi {$to}");
            }
        });
    }

    public function logActivity(string $event, string $description, ?array $properties = null): void
    {
        ActivityLog::create([
            'subject_type' => self::class,
            'subject_id' => $this->id,
            'causer_type' => auth()->check() ? get_class(auth()->user()) : null,
            'causer_id' => auth()->id(),
            'event' => $event,
            'log_name' => 'pendaftaran',
            'description' => $description,
            'properties' => $properties,
        ]);
    }

    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

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
