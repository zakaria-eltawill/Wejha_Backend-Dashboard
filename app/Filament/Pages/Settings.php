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
                        Forms\Components\Tabs\Tab::make('إعدادات عامة / General')
                            ->schema([
                                Forms\Components\TextInput::make('site_name_ar')
                                    ->label('اسم الموقع بالعربية / Site Name (Arabic)')
                                    ->required(),
                                Forms\Components\TextInput::make('site_name_en')
                                    ->label('اسم الموقع بالإنجليزية / Site Name (English)')
                                    ->required(),
                                Forms\Components\Select::make('default_language')
                                    ->label('اللغة الافتراضية / Default Language')
                                    ->options([
                                        'ar' => 'العربية / Arabic',
                                        'en' => 'الإنجليزية / English',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('timezone')
                                    ->label('المنطقة الزمنية / Timezone')
                                    ->options([
                                        'Africa/Tripoli' => 'توقيت طرابلس (Africa/Tripoli)',
                                        'UTC' => 'التوقيت العالمي (UTC)',
                                    ])
                                    ->required(),
                                Forms\Components\Toggle::make('allow_registration')
                                    ->label('السماح بالتسجيل الجديد / Allow Registration')
                                    ->default(true),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('إعدادات البريد / Mail (SMTP)')
                            ->schema([
                                Forms\Components\TextInput::make('smtp_host')
                                    ->label('مضيف SMTP / SMTP Host')
                                    ->required(),
                                Forms\Components\TextInput::make('smtp_port')
                                    ->label('منفذ SMTP / SMTP Port')
                                    ->required(),
                                Forms\Components\TextInput::make('smtp_username')
                                    ->label('اسم مستخدم SMTP / SMTP Username'),
                                Forms\Components\TextInput::make('smtp_password')
                                    ->label('كلمة مرور SMTP / SMTP Password')
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
