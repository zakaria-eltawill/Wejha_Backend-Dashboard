<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';
    protected static ?int $navigationSort = 7;

    public static function getNavigationLabel(): string
    {
        return __('filament-pages.settings.navigation_label');
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) ?? false;
    }

    public function getHeading(): string
    {
        return __('filament-pages.settings.heading');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name_ar' => 'منصة وجهة الرقمية',
            'site_name_en' => 'Wejha Digital Platform',
            'default_language' => 'ar',
            'timezone' => config('app.timezone', 'Africa/Tripoli'),
            'allow_registration' => true,
            'smtp_host' => config('mail.mailers.smtp.host', '127.0.0.1'),
            'smtp_port' => config('mail.mailers.smtp.port', '1025'),
            'smtp_username' => config('mail.mailers.smtp.username', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('filament-pages.settings.tabs.general'))
                            ->schema([
                                Forms\Components\TextInput::make('site_name_ar')
                                    ->label(__('filament-pages.settings.fields.site_name_ar'))
                                    ->required(),
                                Forms\Components\TextInput::make('site_name_en')
                                    ->label(__('filament-pages.settings.fields.site_name_en'))
                                    ->required(),
                                Forms\Components\Select::make('default_language')
                                    ->label(__('filament-pages.settings.fields.default_language'))
                                    ->options([
                                        'ar' => __('filament-pages.settings.fields.default_language_options.ar'),
                                        'en' => __('filament-pages.settings.fields.default_language_options.en'),
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('timezone')
                                    ->label(__('filament-pages.settings.fields.timezone'))
                                    ->options([
                                        'Africa/Tripoli' => 'توقيت طرابلس (Africa/Tripoli)',
                                        'UTC' => 'التوقيت العالمي (UTC)',
                                    ])
                                    ->required(),
                                Forms\Components\Toggle::make('allow_registration')
                                    ->label(__('filament-pages.settings.fields.allow_registration'))
                                    ->default(true),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make(__('filament-pages.settings.tabs.mail'))
                            ->schema([
                                Forms\Components\TextInput::make('smtp_host')
                                    ->label(__('filament-pages.settings.fields.smtp_host'))
                                    ->required(),
                                Forms\Components\TextInput::make('smtp_port')
                                    ->label(__('filament-pages.settings.fields.smtp_port'))
                                    ->required(),
                                Forms\Components\TextInput::make('smtp_username')
                                    ->label(__('filament-pages.settings.fields.smtp_username')),
                                Forms\Components\TextInput::make('smtp_password')
                                    ->label(__('filament-pages.settings.fields.smtp_password'))
                                    ->password(),
                            ])->columns(2),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Notification::make()
            ->title(__('filament-pages.settings.save_success'))
            ->success()
            ->send();
    }
}
