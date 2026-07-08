<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $cluster = UserManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationLabel(): string
    {
        return 'المستخدمون / Users';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم بالكامل / Full Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني / Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور / Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('رقم الجوال / Phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->label('الجنس / Gender')
                            ->options([
                                'male' => 'ذكر / Male',
                                'female' => 'أنثى / Female',
                            ]),
                        Forms\Components\TextInput::make('academic_year')
                            ->label('السنة الدراسية / Year')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('school_name')
                            ->label('المدرسة / School')
                            ->maxLength(255),
                        Forms\Components\Select::make('specialization')
                            ->label('التخصص / Specialization')
                            ->options([
                                'علمي' => 'علمي / Scientific',
                                'أدبي' => 'أدبي / Literary',
                            ]),
                        Forms\Components\Select::make('status')
                            ->label('الحالة / Status')
                            ->options([
                                'active' => 'نشط / Active',
                                'inactive' => 'غير نشط / Inactive',
                                'suspended' => 'معلق / Suspended',
                            ])
                            ->required()
                            ->default('active'),
                        Forms\Components\Select::make('preferred_language')
                            ->label('اللغة المفضلة / Language')
                            ->options([
                                'ar' => 'العربية / Arabic',
                                'en' => 'الإنجليزية / English',
                             ])
                            ->default('ar'),
                        Forms\Components\Select::make('preferred_theme')
                            ->label('المظهر / Theme')
                            ->options([
                                'light' => 'فاتح / Light',
                                'dark' => 'داكن / Dark',
                                'system' => 'النظام / System',
                            ])
                            ->default('system'),
                        Forms\Components\Select::make('roles')
                            ->label('الأدوار / Roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                        Forms\Components\FileUpload::make('avatar')
                            ->label('الصورة الشخصية / Avatar')
                            ->image()
                            ->directory('avatars'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('الصورة / Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم / Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني / Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('رقم الجوال / Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('school_name')
                    ->label('المدرسة / School')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialization')
                    ->label('التخصص / Track')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة / Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('الدور / Role')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء / Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'نشط / Active',
                        'inactive' => 'غير نشط / Inactive',
                        'suspended' => 'معلق / Suspended',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Filament\Resources\UserResource\Pages\CreateUser::route('/create'),
            'edit' => \App\Filament\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
