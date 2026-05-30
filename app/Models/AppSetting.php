<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['key', 'value', 'group'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (! $setting) {
                return $default;
            }

            $mediaUrl = $setting->getFirstMediaUrl($key);
            if ($mediaUrl) {
                return $mediaUrl;
            }

            return $setting->value ?: $default;
        });
    }

    public static function getLogo(): ?string
    {
        return static::get('logo');
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        Cache::forget("app_setting_{$key}");
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(120)
                    ->height(120);
                $this->addMediaConversion('small')
                    ->width(320)
                    ->height(320);
            });

        $this->addMediaCollection('hero_image')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(320)
                    ->height(180);
                $this->addMediaConversion('large')
                    ->width(1920)
                    ->height(1080);
            });
    }

    protected static function booted(): void
    {
        static::saved(fn (self $setting) => Cache::forget("app_setting_{$setting->key}"));
        static::deleted(fn (self $setting) => Cache::forget("app_setting_{$setting->key}"));
    }
}
