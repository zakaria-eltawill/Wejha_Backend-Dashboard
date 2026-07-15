<?php

declare(strict_types=1);

namespace App\Filament\Resources;


use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return __('filament-roles-permissions.permissions.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-roles-permissions.permissions.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-roles-permissions.permissions.plural_model_label');
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
                            ->label(__('filament-roles-permissions.permissions.fields.name'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('guard_name')
                            ->label(__('filament-roles-permissions.permissions.fields.guard_name'))
                            ->required()
                            ->default('web')
                            ->maxLength(255),
                    ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-roles-permissions.permissions.table.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->label(__('filament-roles-permissions.permissions.table.guard_name'))
                    ->searchable(),
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
            'index' => \App\Filament\Resources\PermissionResource\Pages\ListPermissions::route('/'),
            'create' => \App\Filament\Resources\PermissionResource\Pages\CreatePermission::route('/create'),
            'edit' => \App\Filament\Resources\PermissionResource\Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
