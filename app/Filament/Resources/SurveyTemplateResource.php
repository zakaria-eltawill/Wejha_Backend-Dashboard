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
                    ->description('أضف أسئلتك واحدًا تلو الآخر. اسحب من المقبض 🟰 لإعادة الترتيب. / Add your questions one at a time. Drag to reorder.')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->label('')
                            ->relationship('questions')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('نوع السؤال / Question Type')
                                    ->helperText('اختر شكل الإجابة التي تريدها من الطالب. / Choose how the student will answer.')
                                    ->options(
                                        collect(QuestionType::cases())->mapWithKeys(
                                            fn (QuestionType $type) => [$type->value => self::questionTypeIcon($type) . ' ' . $type->labelAr()]
                                        )
                                    )
                                    ->native(false)
                                    ->live()
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('question_text_ar')
                                            ->label('عنوان السؤال بالعربية / Question (Arabic)')
                                            ->placeholder('مثال: ما مدى رضاك عن الفعالية؟')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('question_text_en')
                                            ->label('عنوان السؤال بالإنجليزية / Question (English)')
                                            ->placeholder('e.g. How satisfied were you with the event?')
                                            ->required()
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\Repeater::make('options')
                                    ->label('خيارات الإجابة / Answer Options')
                                    ->helperText('أضف كل خيار في سطر منفصل، بنفس الترتيب الذي سيراه الطالب. / Add each choice on its own line, in the order students will see them.')
                                    ->simple(
                                        Forms\Components\TextInput::make('value')
                                            ->label('الخيار / Option')
                                            ->required()
                                            ->maxLength(255)
                                    )
                                    ->addActionLabel('+ إضافة خيار / Add option')
                                    ->reorderable()
                                    ->defaultItems(2)
                                    ->visible(fn (callable $get) => in_array($get('type'), ['multiple_choice', 'checkbox']))
                                    ->columnSpanFull(),

                                Forms\Components\Toggle::make('is_required')
                                    ->label('سؤال إجباري؟ / Required question?')
                                    ->helperText('لن يتمكن الطالب من إرسال الاستبيان دون الإجابة على هذا السؤال. / Student cannot submit the survey without answering this.')
                                    ->default(true)
                                    ->columnSpanFull(),

                                Forms\Components\Section::make('تفاصيل إضافية (اختياري) / Additional details (optional)')
                                    ->collapsible()
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Textarea::make('description_ar')
                                                    ->label('وصف/توضيح بالعربية / Description (Arabic)')
                                                    ->helperText('نص إضافي يظهر تحت عنوان السؤال لتوضيحه للطالب.')
                                                    ->rows(2),
                                                Forms\Components\Textarea::make('description_en')
                                                    ->label('وصف/توضيح بالإنجليزية / Description (English)')
                                                    ->rows(2),
                                            ]),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('help_text_ar')
                                                    ->label('نص مساعد بالعربية / Help text (Arabic)')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('help_text_en')
                                                    ->label('نص مساعد بالإنجليزية / Help text (English)')
                                                    ->maxLength(255),
                                            ]),
                                        Forms\Components\TextInput::make('score')
                                            ->label('الدرجة / Score')
                                            ->helperText('تُستخدم فقط إذا كان هذا الاستبيان لأغراض التقييم/التصحيح.')
                                            ->numeric()
                                            ->default(0),
                                    ]),
                            ])
                            ->itemLabel(fn (array $state): string => self::questionItemLabel($state))
                            ->orderColumn('sort_order')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action
                                    ->requiresConfirmation()
                                    ->modalHeading('حذف هذا السؤال؟ / Delete this question?')
                                    ->modalDescription('سيتم حذف السؤال وكل إجابات الطلاب عليه نهائيًا، ولا يمكن التراجع عن هذا الإجراء. / This permanently deletes the question and any student answers to it. This cannot be undone.')
                                    ->modalSubmitActionLabel('نعم، احذف / Yes, delete')
                            )
                            ->addActionLabel('+ إضافة سؤال جديد / Add new question')
                            ->defaultItems(1)
                            ->columns(1)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected static function questionTypeIcon(QuestionType $type): string
    {
        return match ($type) {
            QuestionType::TEXT => '✏️',
            QuestionType::TEXTAREA => '📝',
            QuestionType::RATING => '⭐',
            QuestionType::MULTIPLE_CHOICE => '🔘',
            QuestionType::CHECKBOX => '☑️',
            QuestionType::NUMBER => '🔢',
            QuestionType::DATE => '📅',
            QuestionType::EMAIL => '✉️',
            QuestionType::PHONE => '📞',
        };
    }

    protected static function questionItemLabel(array $state): string
    {
        $title = trim($state['question_text_ar'] ?? '');
        $icon = '❓';

        if (!empty($state['type'])) {
            $type = QuestionType::tryFrom($state['type']);
            if ($type) {
                $icon = self::questionTypeIcon($type);
            }
        }

        $required = ($state['is_required'] ?? true) ? '' : ' (اختياري)';

        return $title !== ''
            ? "{$icon} {$title}{$required}"
            : "{$icon} سؤال جديد / New question";
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
                Tables\Actions\Action::make('preview')
                    ->label('معاينة / Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (SurveyTemplate $record) => static::getUrl('preview', ['record' => $record])),

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
            'preview' => \App\Filament\Resources\SurveyTemplateResource\Pages\PreviewSurveyTemplate::route('/{record}/preview'),
        ];
    }
}
