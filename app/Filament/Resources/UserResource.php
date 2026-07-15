<?php

declare(strict_types=1);

namespace App\Filament\Resources;


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
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return __('filament-users.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-users.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-users.plural_model_label');
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
                            ->label(__('filament-users.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->label(__('filament-users.fields.username'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament-users.fields.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament-users.fields.password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_number')
                            ->label(__('filament-users.fields.phone_number'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->label(__('filament-users.fields.gender'))
                            ->options([
                                'male' => __('filament-users.gender.male'),
                                'female' => __('filament-users.gender.female'),
                            ]),
                        Forms\Components\TextInput::make('academic_year')
                            ->label(__('filament-users.fields.academic_year'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('school_name')
                            ->label(__('filament-users.fields.school_name'))
                            ->maxLength(255),
                        Forms\Components\Select::make('specialization')
                            ->label(__('filament-users.fields.specialization'))
                            ->options([
                                'علمي' => __('filament-users.specialization.scientific'),
                                'أدبي' => __('filament-users.specialization.literary'),
                            ]),
                        Forms\Components\Select::make('status')
                            ->label(__('filament-users.fields.status'))
                            ->options([
                                'active' => __('filament-users.status.active'),
                                'inactive' => __('filament-users.status.inactive'),
                                'suspended' => __('filament-users.status.suspended'),
                            ])
                            ->required()
                            ->default('active'),
                        Forms\Components\Select::make('preferred_language')
                            ->label(__('filament-users.fields.preferred_language'))
                            ->options([
                                'ar' => __('filament-users.language.ar'),
                                'en' => __('filament-users.language.en'),
                             ])
                            ->default('ar'),
                        Forms\Components\Select::make('preferred_theme')
                            ->label(__('filament-users.fields.preferred_theme'))
                            ->options([
                                'light' => __('filament-users.theme.light'),
                                'dark' => __('filament-users.theme.dark'),
                                'system' => __('filament-users.theme.system'),
                            ])
                            ->default('system'),
                        Forms\Components\Select::make('roles')
                            ->label(__('filament-users.fields.roles'))
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                        Forms\Components\FileUpload::make('avatar')
                            ->label(__('filament-users.fields.avatar'))
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
                    ->label(__('filament-users.table.avatar'))
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-users.table.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->label(__('filament-users.fields.username'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-users.fields.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('filament-users.fields.phone_number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('school_name')
                    ->label(__('filament-users.fields.school_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialization')
                    ->label(__('filament-users.table.specialization'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-users.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('filament-users.table.role'))
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-users.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => __('filament-users.status.active'),
                        'inactive' => __('filament-users.status.inactive'),
                        'suspended' => __('filament-users.status.suspended'),
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
