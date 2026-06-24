@php
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icon = match (true) {
        in_array($ext, ['pdf']) => 'pdf',
        in_array($ext, ['doc', 'docx']) => 'word',
        in_array($ext, ['xls', 'xlsx', 'csv']) => 'excel',
        in_array($ext, ['ppt', 'pptx']) => 'ppt',
        in_array($ext, ['txt']) => 'text',
        $mime && str_starts_with($mime, 'video/') => 'video',
        $mime && str_starts_with($mime, 'audio/') => 'audio',
        default => 'file',
    };
@endphp

@switch($icon)
    @case('pdf')
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><text x="12" y="16" font-size="7" font-weight="bold" text-anchor="middle" fill="currentColor">PDF</text></svg>
        </span>
        @break
    @case('word')
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 ring-1 ring-blue-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><text x="12" y="16" font-size="6" font-weight="bold" text-anchor="middle" fill="currentColor">DOC</text></svg>
        </span>
        @break
    @case('excel')
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><text x="12" y="16" font-size="6" font-weight="bold" text-anchor="middle" fill="currentColor">XLS</text></svg>
        </span>
        @break
    @case('ppt')
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-orange-600 ring-1 ring-orange-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><text x="12" y="16" font-size="6" font-weight="bold" text-anchor="middle" fill="currentColor">PPT</text></svg>
        </span>
        @break
    @case('text')
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-50 text-neutral-600 ring-1 ring-neutral-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><text x="12" y="16" font-size="6" font-weight="bold" text-anchor="middle" fill="currentColor">TXT</text></svg>
        </span>
        @break
    @case('video')
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600 ring-1 ring-purple-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </span>
        @break
    @case('audio')
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-pink-50 text-pink-600 ring-1 ring-pink-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
        </span>
        @break
    @default
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-50 text-neutral-600 ring-1 ring-neutral-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </span>
@endswitch
