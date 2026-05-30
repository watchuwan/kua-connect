<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Visitor extends Model
{
    protected $fillable = ['ip_address', 'user_agent', 'page'];

    public static function today(): int
    {
        return Cache::remember('visitor_today', 60, function () {
            return static::whereDate('created_at', today())
                ->distinct('ip_address')
                ->count('ip_address');
        });
    }

    public static function thisMonth(): int
    {
        return Cache::remember('visitor_month', 3600, function () {
            return static::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->distinct('ip_address')
                ->count('ip_address');
        });
    }

    public static function total(): int
    {
        return Cache::remember('visitor_total', 3600, function () {
            return static::distinct('ip_address')->count('ip_address');
        });
    }

    public static function record(string $page = '/'): void
    {
        $ip = request()->ip();

        $exists = static::whereDate('created_at', today())
            ->where('ip_address', $ip)
            ->exists();

        if ($exists) {
            return;
        }

        static::create([
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'page' => $page,
        ]);
        Cache::forget('visitor_today');
        Cache::forget('visitor_month');
        Cache::forget('visitor_total');
    }
}
