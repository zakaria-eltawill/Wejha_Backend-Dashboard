<?php

declare(strict_types=1);

namespace App\Filament\Resources;


use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return __('filament-notifications-audit.audit_log.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-notifications-audit.audit_log.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-notifications-audit.audit_log.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label(__('filament-notifications-audit.audit_log.fields.user_name'))
                            ->disabled(),
                        Forms\Components\TextInput::make('action')
                            ->label(__('filament-notifications-audit.audit_log.fields.action'))
                            ->disabled(),
                        Forms\Components\TextInput::make('entity')
                            ->label(__('filament-notifications-audit.audit_log.fields.entity'))
                            ->disabled(),
                        Forms\Components\TextInput::make('entity_id')
                            ->label(__('filament-notifications-audit.audit_log.fields.entity_id'))
                            ->disabled(),
                        Forms\Components\TextInput::make('ip_address')
                            ->label(__('filament-notifications-audit.audit_log.fields.ip_address'))
                            ->disabled(),
                        Forms\Components\TextInput::make('user_agent')
                            ->label(__('filament-notifications-audit.audit_log.fields.user_agent'))
                            ->disabled(),
                        Forms\Components\KeyValue::make('old_values')
                            ->label(__('filament-notifications-audit.audit_log.fields.old_values'))
                            ->disabled(),
                        Forms\Components\KeyValue::make('new_values')
                            ->label(__('filament-notifications-audit.audit_log.fields.new_values'))
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label(__('filament-notifications-audit.audit_log.fields.created_at'))
                            ->disabled(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament-notifications-audit.audit_log.table.user'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->label(__('filament-notifications-audit.audit_log.table.action'))
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entity')
                    ->label(__('filament-notifications-audit.audit_log.table.entity'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('filament-notifications-audit.audit_log.table.ip_address')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-notifications-audit.audit_log.table.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'create' => __('filament-notifications-audit.audit_log.action_type.create'),
                        'update' => __('filament-notifications-audit.audit_log.action_type.update'),
                        'delete' => __('filament-notifications-audit.audit_log.action_type.delete'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Read-only
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\AuditLogResource\Pages\ListAuditLogs::route('/'),
        ];
    }
}
