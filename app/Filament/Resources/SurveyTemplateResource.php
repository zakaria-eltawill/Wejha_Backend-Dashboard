<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\SurveyManagement;
use App\Models\SurveyTemplate;
use App\Enums\QuestionType;
use App\Services\SurveyTemplateService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SurveyTemplateResource extends Resource
{
    protected static ?string $model = SurveyTemplate::class;
    protected static ?string $cluster = SurveyManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function getNavigationLabel(): string
    {
        return 'نماذج الاستبيانات / Templates';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('version')
                            ->default('1.0')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'مسودة / Draft',
                                'active' => 'نشط / Active',
                                'archived' => 'مؤرشف / Archived',
                            ])
                            ->required()
                            ->default('draft'),
                        Forms\Components\TextInput::make('category')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_reusable')
                            ->default(true),
                        Forms\Components\Textarea::make('description_ar')
                            ->rows(2),
                        Forms\Components\Textarea::make('description_en')
                            ->rows(2),
                    ])->columns(2),

                Forms\Components\Section::make('أسئلة الاستبيان / Survey Questions')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship('questions')
                            ->schema([
                                Forms\Components\TextInput::make('question_text_ar')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('question_text_en')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->options(collect(QuestionType::cases())->mapWithKeys(fn ($q) => [$q->value => $q->labelAr()]))
                                    ->reactive()
                                    ->required(),
                                Forms\Components\KeyValue::make('options')
                                    ->label('خيارات الإجابة / Answer Options')
                                    ->keyLabel('المفتاح / Key')
                                    ->valueLabel('القيمة / Label')
                                    ->visible(fn (callable $get) => in_array($get('type'), ['multiple_choice', 'checkbox', 'rating'])),
                                Forms\Components\Toggle::make('is_required')
                                    ->default(true),
                                Forms\Components\TextInput::make('score')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->orderColumn('sort_order')
                            ->defaultItems(1)
                            ->columns(2)
                            ->columnSpanFull()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'archived' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\ToggleColumn::make('is_reusable'),
                Tables\Columns\TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('عدد الأسئلة'),
            ])
            ->actions([
                Tables\Actions\Action::make('clone')
                    ->label('نسخ / Clone')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->action(fn (SurveyTemplate $record) => app(SurveyTemplateService::class)->cloneTemplate($record->id)),
                
                Tables\Actions\Action::make('export')
                    ->label('تصدير JSON')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function (SurveyTemplate $record) {
                        $data = $record->load('questions')->toArray();
                        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        return response()->streamDownload(
                            fn () => print($json),
                            "template_" . str_replace(' ', '_', $record->name_en) . ".json"
                        );
                    }),

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
            'index' => \App\Filament\Resources\SurveyTemplateResource\Pages\ListSurveyTemplates::route('/'),
            'create' => \App\Filament\Resources\SurveyTemplateResource\Pages\CreateSurveyTemplate::route('/create'),
            'edit' => \App\Filament\Resources\SurveyTemplateResource\Pages\EditSurveyTemplate::route('/{record}/edit'),
        ];
    }
}
