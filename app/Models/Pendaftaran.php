<?php

namespace App\Models;

use App\Enums\StatusPendaftaran;
use App\Services\PdfGenerator;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Pendaftaran extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia, SoftDeletes;

    protected static function booted(): void
    {
        static::created(function (Pendaftaran $pendaftaran) {
            $pendaftaran->logActivity('created', 'Pendaftaran baru dengan nomor antrean ' . $pendaftaran->nomor_antrean);
        });

        static::updated(function (Pendaftaran $pendaftaran) {
            if ($pendaftaran->isDirty('status')) {
                $from = $pendaftaran->getOriginal('status')->value ?? $pendaftaran->getOriginal('status');
                $to = $pendaftaran->status->value;
                $slug = $pendaftaran->pelayanan?->slug;
                $pendaftaran->logActivity('status_updated', "Status berubah dari {$from} menjadi {$to}");

                if ($to === StatusPendaftaran::Selesai->value && in_array($slug, ['rekomendasi-nikah-keluar', 'surat-keterangan-mualaf'])) {
                    PdfGenerator::generate($pendaftaran);
                }

                if ($to === StatusPendaftaran::JadwalDitugaskan->value && $slug === 'kalibrasi-arah-kiblat') {
                    WhatsAppService::notifyJadwalKunjungan($pendaftaran);
                }
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
    protected $fillable = ['nomor_antrean', 'pelayanan_id', 'penghulu_id', 'data', 'status', 'catatan', 'no_surat', 'jadwal_kedatangan', 'jadwal_bimwin', 'derajat_kiblat', 'waktu_dilayani', 'waktu_selesai'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'status' => StatusPendaftaran::class,
            'waktu_dilayani' => 'datetime',
            'waktu_selesai' => 'datetime',
            'jadwal_kedatangan' => 'datetime',
            'jadwal_bimwin' => 'datetime',
        ];
    }

    public function getAllowedTransitions(): array
    {
        return StatusPendaftaran::allowedTransitions(
            $this->status?->value,
            $this->pelayanan?->slug,
        );
    }

    public function canTransitionTo(StatusPendaftaran $target): bool
    {
        return in_array($target, $this->getAllowedTransitions(), true);
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

    public function penghulu(): BelongsTo
    {
        return $this->belongsTo(Penghulu::class, 'penghulu_id');
    }
}
