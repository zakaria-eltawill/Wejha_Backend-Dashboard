<?php

declare(strict_types=1);

namespace App\Filament\Resources;


use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return __('filament-roles-permissions.roles.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-roles-permissions.roles.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-roles-permissions.roles.plural_model_label');
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
                            ->label(__('filament-roles-permissions.roles.fields.name'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('guard_name')
                            ->label(__('filament-roles-permissions.roles.fields.guard_name'))
                            ->required()
                            ->default('web')
                            ->maxLength(255),
                        Forms\Components\Select::make('permissions')
                            ->label(__('filament-roles-permissions.roles.fields.permissions'))
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload(),
                    ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-roles-permissions.roles.table.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->label(__('filament-roles-permissions.roles.table.guard_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('permissions.name')
                    ->label(__('filament-roles-permissions.roles.table.permissions'))
                    ->badge()
                    ->color('warning')
                    ->limitList(5),
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
            'index' => \App\Filament\Resources\RoleResource\Pages\ListRoles::route('/'),
            'create' => \App\Filament\Resources\RoleResource\Pages\CreateRole::route('/create'),
            'edit' => \App\Filament\Resources\RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
