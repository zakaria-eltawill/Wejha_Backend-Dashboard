<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-events.relation_managers.activities.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description_ar')
            ->columns([
                Tables\Columns\TextColumn::make('description_ar')
                    ->label(__('filament-events.table.columns.activity_ar'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description_en')
                    ->label(__('filament-events.table.columns.activity_en'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('occurred_at')
                    ->label(__('filament-events.table.columns.occurred_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Read-only log
            ])
            ->bulkActions([
                //
            ]);
    }
}
