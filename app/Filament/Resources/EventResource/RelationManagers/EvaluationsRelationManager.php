<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Enums\EvaluationType;
use App\Models\EventEvaluation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EvaluationsRelationManager extends RelationManager
{
    protected static string $relationship = 'evaluations';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-events.relation_managers.evaluations.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_template_id')
                    ->label(__('filament-events.fields.survey_template'))
                    ->relationship('template', 'name_ar')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('evaluation_type')
                    ->label(__('filament-events.fields.evaluation_type'))
                    ->options([
                        'pre' => __('filament-events.evaluation_type_options.pre'),
                        'post' => __('filament-events.evaluation_type_options.post'),
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('filament-events.fields.is_active'))
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('template.name_ar')
                    ->label(__('filament-events.fields.survey_template'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation_type')
                    ->label(__('filament-events.fields.evaluation_type'))
                    ->badge()
                    ->color(fn (EvaluationType $state): string => match ($state->value) {
                        'pre' => 'info',
                        'post' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (EvaluationType $state): string => match ($state->value) {
                        'pre' => __('filament-events.evaluation_type_badge.pre'),
                        'post' => __('filament-events.evaluation_type_badge.post'),
                        default => $state->name,
                    }),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('filament-events.fields.is_active')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('evaluation_type')
                    ->label(__('filament-events.fields.evaluation_type'))
                    ->options([
                        'pre' => __('filament-events.evaluation_type_badge.pre'),
                        'post' => __('filament-events.evaluation_type_badge.post'),
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('filament-events.actions.link_survey')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label(__('filament-events.actions.unlink')),
            ]);
    }
}
