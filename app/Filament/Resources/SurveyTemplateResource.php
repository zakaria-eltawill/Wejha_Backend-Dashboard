<?php

declare(strict_types=1);

namespace App\Filament\Resources;

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
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return 'إدارة الاستبيانات / Surveys';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label('اسم النموذج بالعربية / Name (Arabic)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->label('اسم النموذج بالإنجليزية / Name (English)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('version')
                            ->label('الإصدار / Version')
                            ->default('1.0')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('status')
                            ->label('الحالة / Status')
                            ->options([
                                'draft' => 'مسودة / Draft',
                                'active' => 'نشط / Active',
                                'archived' => 'مؤرشف / Archived',
                            ])
                            ->required()
                            ->default('draft'),
                        Forms\Components\TextInput::make('category')
                            ->label('التصنيف / Category')
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('نوع الاستبيان / Survey Type')
                            ->options([
                                'pre' => 'استبيان قبلي (عند التسجيل) / Pre-Assessment',
                                'post' => 'استبيان بعدي (بعد الحضور) / Post-Assessment',
                            ])
                            ->required()
                            ->default('pre'),
                        Forms\Components\Toggle::make('is_reusable')
                            ->label('قابل لإعادة الاستخدام / Reusable')
                            ->default(true),
                        Forms\Components\Textarea::make('description_ar')
                            ->label('الوصف بالعربية / Description (Arabic)')
                            ->rows(2),
                        Forms\Components\Textarea::make('description_en')
                            ->label('الوصف بالإنجليزية / Description (English)')
                            ->rows(2),
                    ])->columns(2),

                Forms\Components\Section::make('أسئلة الاستبيان / Survey Questions')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->label('الأسئلة / Questions')
                            ->relationship('questions')
                            ->schema([
                                Forms\Components\TextInput::make('question_text_ar')
                                    ->label('نص السؤال بالعربية / Question Text (Arabic)')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('question_text_en')
                                    ->label('نص السؤال بالإنجليزية / Question Text (English)')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->label('نوع السؤال / Question Type')
                                    ->options(collect(QuestionType::cases())->mapWithKeys(fn ($q) => [$q->value => $q->labelAr()]))
                                    ->reactive()
                                    ->required(),
                                Forms\Components\KeyValue::make('options')
                                    ->label('خيارات الإجابة / Answer Options')
                                    ->keyLabel('المفتاح / Key')
                                    ->valueLabel('القيمة / Label')
                                    ->visible(fn (callable $get) => in_array($get('type'), ['multiple_choice', 'checkbox', 'rating'])),
                                Forms\Components\Toggle::make('is_required')
                                    ->label('إجباري / Required')
                                    ->default(true),
                                Forms\Components\TextInput::make('score')
                                    ->label('الدرجة / Score')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('ترتيب السؤال / Order')
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
                    ->label('اسم الاستبيان / Survey Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->label('الإصدار / Version')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع الاستبيان / Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pre' => 'info',
                        'post' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pre' => 'استبيان قبلي / Pre-Assessment',
                        'post' => 'استبيان بعدي / Post-Assessment',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة / Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'archived' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\ToggleColumn::make('is_reusable')
                    ->label('قابل لإعادة الاستخدام / Reusable'),
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
