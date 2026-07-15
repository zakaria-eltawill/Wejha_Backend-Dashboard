<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\SurveyResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SurveyResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'surveyResponses';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-events.relation_managers.survey_responses.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')
                    ->label(__('filament-events.fields.student_name'))
                    ->disabled(),
                Forms\Components\TextInput::make('eventEvaluation.template.name_ar')
                    ->label(__('filament-events.fields.template'))
                    ->disabled(),
                Forms\Components\TextInput::make('question.question_text_ar')
                    ->label(__('filament-events.fields.question'))
                    ->disabled(),
                Forms\Components\Textarea::make('response_text')
                    ->label(__('filament-events.fields.answer'))
                    ->disabled()
                    ->rows(3),
                Forms\Components\DateTimePicker::make('submitted_at')
                    ->label(__('filament-events.fields.submitted_at'))
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('response_text')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament-events.table.columns.participant'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventEvaluation.template.name_ar')
                    ->label(__('filament-events.table.columns.survey'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventEvaluation.evaluation_type')
                    ->label(__('filament-events.table.columns.type'))
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value) {
                        'pre' => 'info',
                        'post' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state?->value) {
                        'pre' => __('filament-events.evaluation_badge_short.pre'),
                        'post' => __('filament-events.evaluation_badge_short.post'),
                        default => '',
                    }),
                Tables\Columns\TextColumn::make('question.question_text_ar')
                    ->label(__('filament-events.fields.question'))
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('response_text')
                    ->label(__('filament-events.fields.answer'))
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label(__('filament-events.fields.submitted_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('eventEvaluation.evaluation_type')
                    ->label(__('filament-events.fields.evaluation_type'))
                    ->options([
                        'pre' => __('filament-events.filter_evaluation_type.pre'),
                        'post' => __('filament-events.filter_evaluation_type.post'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('filament-events.actions.view_details')),
            ]);
    }
}
