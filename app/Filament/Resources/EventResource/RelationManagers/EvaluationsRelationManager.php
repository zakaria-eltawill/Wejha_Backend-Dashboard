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
        return 'التقييمات والاستبيانات المربوطة / Linked Surveys';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_template_id')
                    ->label('نموذج الاستبيان / Survey Template')
                    ->relationship('template', 'name_ar')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('evaluation_type')
                    ->label('نوع الاستبيان / Survey Type')
                    ->options([
                        'pre' => 'استبيان قبلي (عند التسجيل) / Pre-Assessment',
                        'post' => 'استبيان بعدي (بعد الحضور) / Post-Assessment',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشط / Active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('template.name_ar')
                    ->label('نموذج الاستبيان / Survey Template')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation_type')
                    ->label('نوع الاستبيان / Survey Type')
                    ->badge()
                    ->color(fn (EvaluationType $state): string => match ($state->value) {
                        'pre' => 'info',
                        'post' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (EvaluationType $state): string => match ($state->value) {
                        'pre' => 'استبيان قبلي / Pre-Assessment',
                        'post' => 'استبيان بعدي / Post-Assessment',
                        default => $state->name,
                    }),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('نشط / Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('evaluation_type')
                    ->label('نوع الاستبيان')
                    ->options([
                        'pre' => 'استبيان قبلي / Pre-Assessment',
                        'post' => 'استبيان بعدي / Post-Assessment',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('ربط استبيان جديد / Link Survey'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('إلغاء الربط / Unlink'),
            ]);
    }
}
