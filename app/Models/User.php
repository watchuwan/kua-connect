<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(["name", "email", "password", "is_active"])]
#[Hidden(["password", "remember_token"])]
class User extends Authenticatable implements FilamentUser, HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "is_active" => "boolean",
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection("avatar")
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion("thumb")
                    ->width(80)
                    ->height(80);
                $this->addMediaConversion("small")
                    ->width(160)
                    ->height(160);
            });
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl("avatar", "thumb");
    }
}
