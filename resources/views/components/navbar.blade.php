@php
$navItems = [
    ['label' => 'Beranda', 'href' => '/'],
    ['label' => 'Lacak Antrean', 'href' => '/tracking'],
    ['label' => 'Layanan', 'href' => '#', 'children' => [
        ['label' => 'Daftar Layanan', 'href' => '/layanan'],
        ['label' => 'OPD', 'href' => '/opd'],
    ]],
];
@endphp

<header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 border-b border-neutral-200/60 bg-white/80 backdrop-blur-lg">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2">
            @php
                try { $logo = \App\Models\AppSetting::getLogo(); } catch(\Throwable $e) { $logo = null; }
                try { $portalName = \App\Models\AppSetting::get('nama_portal', 'Tangkab Melayani'); } catch(\Throwable $e) { $portalName = 'Tangkab Melayani'; }
            @endphp
            @if($logo)
                <img src="{{ $logo }}" alt="Logo" class="h-9 w-9 rounded-lg object-contain">
            @else
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-white font-display font-bold text-sm">TM</span>
            @endif
            <span class="font-display text-lg font-bold text-neutral-900">{{ $portalName }}</span>
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
            @foreach($navItems as $item)
                @if(isset($item['children']))
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 transition-colors hover:bg-neutral-100 hover:text-primary">
                            {{ $item['label'] }}
                            <svg class="h-4 w-4 transition-transform" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition.origin.top class="absolute left-0 mt-1 w-48 rounded-xl border border-neutral-100 bg-white p-2 shadow-xl" style="display:none">
                            @foreach($item['children'] as $child)
                                <a href="{{ $child['href'] }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-neutral-700 transition-colors hover:bg-primary-light/10 hover:text-primary">{{ $child['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['href'] }}" class="rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 transition-colors hover:bg-neutral-100 hover:text-primary {{ request()->is(trim($item['href'], '/') ?: '/') ? 'text-primary bg-primary-light/10' : '' }}" wire:navigate>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Mobile Toggle --}}
        <div class="flex items-center gap-3">
            <button @click="mobileOpen = true" class="rounded-lg p-2 text-neutral-700 hover:bg-neutral-100 lg:hidden" aria-label="Buka menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile Dropdown --}}
    <div x-cloak x-show="mobileOpen" x-transition @click.outside="mobileOpen = false" class="absolute top-full left-0 right-0 z-50 border-b border-neutral-200 bg-white shadow-lg lg:hidden">
        <div class="mx-auto max-w-7xl px-4 py-4 space-y-1">
            @foreach($navItems as $item)
                @if(isset($item['children']))
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100">
                            {{ $item['label'] }}
                            <svg class="h-4 w-4 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="pl-4 space-y-1 mt-1">
                            @foreach($item['children'] as $child)
                                <a href="{{ $child['href'] }}" wire:navigate @click="mobileOpen = false" class="block rounded-lg px-3 py-2 text-sm text-neutral-600 hover:bg-neutral-100 hover:text-primary">{{ $child['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['href'] }}" wire:navigate @click="mobileOpen = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 hover:text-primary">{{ $item['label'] }}</a>
                @endif
            @endforeach
        </div>
    </div>
</header>
