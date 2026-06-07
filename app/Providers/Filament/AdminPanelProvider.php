<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\CustomLogin;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Navigation\NavigationGroup;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel->configureUsing(function () {
            Table::configureUsing(function (Table $table): void {
                $table->striped()->paginationMode(PaginationMode::Cursor);
            });
            //configure components
            CreateRecord::disableCreateAnother();
            CreateAction::configureUsing(function (CreateAction $action): void {
                $action->button()->createAnother(false)->color(Color::Emerald);
            });
            EditAction::configureUsing(function (EditAction $action): void {
                $action->button()->color(Color::Blue);
            });
            ViewAction::configureUsing(function (ViewAction $action): void {
                $action->button();
            });
            DeleteAction::configureUsing(function (DeleteAction $action): void {
                $action->button();
            });
        });
        return $panel
            ->default()
            ->id("admin")
            ->path("admin")
            ->brandName("KUA Connect")
            ->viteTheme("resources/css/filament/admin/theme.css")
            ->login(CustomLogin::class)
            ->colors([
                "primary" => Color::Blue,
            ])
            ->discoverResources(
                in: app_path("Filament/Resources"),
                for: "App\Filament\Resources",
            )
            ->discoverPages(
                in: app_path("Filament/Pages"),
                for: "App\Filament\Pages",
            )
            ->pages([Dashboard::class])
            ->discoverWidgets(
                in: app_path("Filament/Widgets"),
                for: "App\Filament\Widgets",
            )
            ->widgets([\App\Filament\Widgets\CustomAccountWidget::class])
            ->spa(true)
            ->breadcrumbs(false)
            ->resourceEditPageRedirect("index")
            ->resourceCreatePageRedirect("index")
            ->maxContentWidth("full")
            ->navigationGroups([
                NavigationGroup::make("Pelayanan"),
                NavigationGroup::make("Master Data"),
                NavigationGroup::make("Pengaturan"),
                NavigationGroup::make("Administrator"),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup("Administrator")
                    ->navigationSort(1)
                    ->gridColumns([
                        "default" => 1,
                        "sm" => 1,
                        "lg" => 2,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        "default" => 1,
                        "sm" => 2,
                        "lg" => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        "default" => 1,
                        "sm" => 2,
                    ]),
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
