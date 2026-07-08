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
        return 'إجابات الطلاب على الاستبيانات / Student Survey Answers';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')
                    ->label('اسم الطالب / Student Name')
                    ->disabled(),
                Forms\Components\TextInput::make('eventEvaluation.template.name_ar')
                    ->label('نموذج الاستبيان / Template')
                    ->disabled(),
                Forms\Components\TextInput::make('question.question_text_ar')
                    ->label('السؤال / Question')
                    ->disabled(),
                Forms\Components\Textarea::make('response_text')
                    ->label('الإجابة / Answer')
                    ->disabled()
                    ->rows(3),
                Forms\Components\DateTimePicker::make('submitted_at')
                    ->label('تاريخ الإرسال / Submitted At')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('response_text')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المشارك / Participant')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventEvaluation.template.name_ar')
                    ->label('الاستبيان / Survey')
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventEvaluation.evaluation_type')
                    ->label('النوع / Type')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value) {
                        'pre' => 'info',
                        'post' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state?->value) {
                        'pre' => 'قبلي / Pre',
                        'post' => 'بعدي / Post',
                        default => '',
                    }),
                Tables\Columns\TextColumn::make('question.question_text_ar')
                    ->label('السؤال / Question')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('response_text')
                    ->label('الإجابة / Answer')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('تاريخ الإرسال / Submitted At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('eventEvaluation.evaluation_type')
                    ->label('نوع الاستبيان')
                    ->options([
                        'pre' => 'قبلي / Pre-Assessment',
                        'post' => 'بعدي / Post-Assessment',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض التفاصيل / View'),
            ]);
    }
}
