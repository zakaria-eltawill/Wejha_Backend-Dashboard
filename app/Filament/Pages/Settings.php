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

    public static function getNavigationLabel(): string
    {
        return 'إعدادات النظام / Settings';
    }

    public function getHeading(): string
    {
        return 'إعدادات النظام / System Settings';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name_ar' => 'منصة وجهة الرقمية',
            'site_name_en' => 'Wejha Digital Platform',
            'default_language' => 'ar',
            'timezone' => 'Asia/Riyadh',
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
                        Forms\Components\Tabs\Tab::make('إعدادات عامة / General')
                            ->schema([
                                Forms\Components\TextInput::make('site_name_ar')
                                    ->required(),
                                Forms\Components\TextInput::make('site_name_en')
                                    ->required(),
                                Forms\Components\Select::make('default_language')
                                    ->options([
                                        'ar' => 'العربية / Arabic',
                                        'en' => 'الإنجليزية / English',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('timezone')
                                    ->options([
                                        'Asia/Riyadh' => 'توقيت الرياض (Asia/Riyadh)',
                                        'UTC' => 'التوقيت العالمي (UTC)',
                                    ])
                                    ->required(),
                                Forms\Components\Toggle::make('allow_registration')
                                    ->default(true),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('إعدادات البريد / Mail (SMTP)')
                            ->schema([
                                Forms\Components\TextInput::make('smtp_host')
                                    ->required(),
                                Forms\Components\TextInput::make('smtp_port')
                                    ->required(),
                                Forms\Components\TextInput::make('smtp_username'),
                                Forms\Components\TextInput::make('smtp_password')
                                    ->password(),
                            ])->columns(2),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح / Settings saved successfully.')
            ->success()
            ->send();
    }
}
