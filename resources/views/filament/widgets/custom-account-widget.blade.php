@php
    $user = filament()->auth()->user();
@endphp

<div class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-4">
            <x-filament-panels::avatar.user
                size="lg"
                :user="$user"
                loading="lazy"
            />

            <div class="flex-1">
                <h2 class="text-base font-semibold text-brand-900 dark:text-white">
                    {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                </h2>

                <p class="text-sm text-brand-500 dark:text-brand-400">
                    {{ filament()->getUserName($user) }}
                </p>
            </div>

            <form
                action="{{ filament()->getLogoutUrl() }}"
                method="post"
            >
                @csrf

                <x-filament::button
                    color="gray"
                    :icon="\Filament\Support\Icons\Heroicon::ArrowLeftEndOnRectangle"
                    :icon-alias="\Filament\View\PanelsIconAlias::WIDGETS_ACCOUNT_LOGOUT_BUTTON"
                    labeled-from="sm"
                    tag="button"
                    type="submit"
                >
                    {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>
</div>
