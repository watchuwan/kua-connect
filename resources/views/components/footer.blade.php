<footer class="bg-neutral-900 text-neutral-300">
    @php
        try { $logo = \App\Models\AppSetting::getLogo(); } catch(\Throwable $e) { $logo = null; }
        try { $portalName = \App\Models\AppSetting::get('nama_portal', 'KUA Connect'); } catch(\Throwable $e) { $portalName = 'KUA Connect'; }
        try { $deskripsi = \App\Models\AppSetting::get('deskripsi_portal', 'Portal Pelayanan Publik KUA Kecamatan'); } catch(\Throwable $e) { $deskripsi = 'Portal Pelayanan Publik KUA Kecamatan'; }
        try { $alamat = \App\Models\AppSetting::get('alamat', 'KUA Kecamatan, Banten'); } catch(\Throwable $e) { $alamat = 'KUA Kecamatan, Banten'; }
        try { $email = \App\Models\AppSetting::get('email', 'admin@kua-connect.go.id'); } catch(\Throwable $e) { $email = 'admin@kua-connect.go.id'; }
        try { $telepon = \App\Models\AppSetting::get('telepon'); } catch(\Throwable $e) { $telepon = null; }
        try { $fb = \App\Models\AppSetting::get('facebook'); } catch(\Throwable $e) { $fb = null; }
        try { $ig = \App\Models\AppSetting::get('instagram'); } catch(\Throwable $e) { $ig = null; }
        try { $tw = \App\Models\AppSetting::get('twitter'); } catch(\Throwable $e) { $tw = null; }
        try { $yt = \App\Models\AppSetting::get('youtube'); } catch(\Throwable $e) { $yt = null; }
        try { $tiktok = \App\Models\AppSetting::get('tiktok'); } catch(\Throwable $e) { $tiktok = null; }
        try { $visitorToday = \App\Models\Visitor::today(); } catch(\Throwable $e) { $visitorToday = 0; }
        try { $visitorMonth = \App\Models\Visitor::thisMonth(); } catch(\Throwable $e) { $visitorMonth = 0; }
        try { $visitorTotal = \App\Models\Visitor::total(); } catch(\Throwable $e) { $visitorTotal = 0; }
    @endphp

    {{-- Main footer --}}
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        {{-- Top: Brand row --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-10 border-b border-neutral-800">
            <div class="flex items-center gap-3">
                @if($logo)
                <img src="{{ $logo }}" alt="Logo" class="h-14 w-14 rounded-xl object-contain" loading="lazy">
                @else
                <span class="flex h-14 w-14 items-center justify-center rounded-xl bg-primary text-white font-display font-bold text-lg">KC</span>
                @endif
                <div>
                    <span class="font-display text-lg font-bold text-white">{{ $portalName }}</span>
                    <p class="text-xs text-neutral-500">KUA Kecamatan</p>
                </div>
            </div>
            {{-- Social Media --}}
            <div class="flex gap-2">
                @if($fb)
                <a href="{{ $fb }}" target="_blank" class="flex h-9 w-9 items-center justify-center rounded-xl bg-neutral-800 text-neutral-400 hover:bg-primary hover:text-white transition-colors" aria-label="Facebook">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                @endif
                @if($ig)
                <a href="{{ $ig }}" target="_blank" class="flex h-9 w-9 items-center justify-center rounded-xl bg-neutral-800 text-neutral-400 hover:bg-primary hover:text-white transition-colors" aria-label="Instagram">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                @endif
                @if($tw)
                <a href="{{ $tw }}" target="_blank" class="flex h-9 w-9 items-center justify-center rounded-xl bg-neutral-800 text-neutral-400 hover:bg-primary hover:text-white transition-colors" aria-label="Twitter">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
                @endif
                @if($yt)
                <a href="{{ $yt }}" target="_blank" class="flex h-9 w-9 items-center justify-center rounded-xl bg-neutral-800 text-neutral-400 hover:bg-primary hover:text-white transition-colors" aria-label="YouTube">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zM9 16V8l8 3.993L9 16z"/></svg>
                </a>
                @endif
                @if($tiktok)
                <a href="{{ $tiktok }}" target="_blank" class="flex h-9 w-9 items-center justify-center rounded-xl bg-neutral-800 text-neutral-400 hover:bg-primary hover:text-white transition-colors" aria-label="TikTok">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
                @endif
            </div>
        </div>

        {{-- Middle: 4 columns --}}
        <div class="grid grid-cols-1 gap-8 pt-10 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Tentang --}}
            <div>
                <h4 class="font-display text-sm font-semibold uppercase tracking-wider text-white mb-4">Tentang</h4>
                <p class="text-sm leading-relaxed text-neutral-400 mb-4">{{ $deskripsi }}</p>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $alamat }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>{{ $email }}</span>
                    </li>
                </ul>
            </div>

            {{-- Navigasi --}}
            <div>
                <h4 class="font-display text-sm font-semibold uppercase tracking-wider text-white mb-4">Navigasi</h4>
                <ul class="space-y-2.5">
                    <li><a href="/" wire:navigate class="text-sm text-neutral-400 hover:text-primary-light transition-colors">Beranda</a></li>
                    <li><a href="/tracking" wire:navigate class="text-sm text-neutral-400 hover:text-primary-light transition-colors">Lacak Antrean</a></li>
                    <li><a href="/layanan" wire:navigate class="text-sm text-neutral-400 hover:text-primary-light transition-colors">Layanan</a></li>
                </ul>
            </div>

            {{-- Link Penting --}}
            <div>
                <h4 class="font-display text-sm font-semibold uppercase tracking-wider text-white mb-4">Link Penting</h4>
                @php
                    $links = [
                        ['key' => 'link_pemkab', 'label' => 'Kemenag RI'],
                        ['key' => 'link_satu_data_nasional', 'label' => 'Satu Data Indonesia'],
                        ['key' => 'link_bappenas', 'label' => 'Bappenas RI'],
                        ['key' => 'link_bps', 'label' => 'BPS RI'],
                    ];
                @endphp
                <ul class="space-y-2.5">
                    @foreach($links as $link)
                        @php $url = \App\Models\AppSetting::get($link['key']); @endphp
                        @if($url)
                            <li>
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-primary-light transition-colors">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            {{-- Statistik Pengunjung --}}
            <div>
                <h4 class="font-display text-sm font-semibold uppercase tracking-wider text-white mb-4">Pengunjung</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between rounded-xl bg-neutral-800/80 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-neutral-400">Hari Ini</span>
                        </div>
                        <span class="text-sm font-bold text-white tabular-nums">{{ number_format($visitorToday) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-neutral-800/80 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-sm text-neutral-400">Bulan Ini</span>
                        </div>
                        <span class="text-sm font-bold text-white tabular-nums">{{ number_format($visitorMonth) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-neutral-800/80 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                            <span class="text-sm text-neutral-400">Total</span>
                        </div>
                        <span class="text-sm font-bold text-white tabular-nums">{{ number_format($visitorTotal) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="border-t border-neutral-800">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-neutral-500">&copy; {{ date('Y') }} KUA Connect. Semua Hak Dilindungi.</p>
        </div>
    </div>
</footer>
