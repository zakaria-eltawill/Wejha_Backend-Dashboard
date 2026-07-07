<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\SystemReports;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;
    protected static ?string $cluster = SystemReports::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    public static function getNavigationLabel(): string
    {
        return 'سجلات المراجعة والأمان / Audit Logs';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->disabled(),
                        Forms\Components\TextInput::make('action')
                            ->disabled(),
                        Forms\Components\TextInput::make('entity')
                            ->disabled(),
                        Forms\Components\TextInput::make('entity_id')
                            ->disabled(),
                        Forms\Components\TextInput::make('ip_address')
                            ->disabled(),
                        Forms\Components\TextInput::make('user_agent')
                            ->disabled(),
                        Forms\Components\KeyValue::make('old_values')
                            ->label('القيم السابقة / Old Values')
                            ->disabled(),
                        Forms\Components\KeyValue::make('new_values')
                            ->label('القيم الجديدة / New Values')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->disabled(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المستخدم / User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('العملية / Action')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entity')
                    ->label('الجدول / Entity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('الوقت / Logged At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'create' => 'إنشاء / Create',
                        'update' => 'تحديث / Update',
                        'delete' => 'حذف / Delete',
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
