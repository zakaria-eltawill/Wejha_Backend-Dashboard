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
        return __('filament-surveys.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-surveys.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-surveys.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('filament-surveys.fields.name_ar'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('filament-surveys.fields.name_en'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('version')
                            ->label(__('filament-surveys.fields.version'))
                            ->default('1.0')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('status')
                            ->label(__('filament-surveys.fields.status'))
                            ->options([
                                'draft' => __('filament-surveys.status.draft'),
                                'active' => __('filament-surveys.status.active'),
                                'archived' => __('filament-surveys.status.archived'),
                            ])
                            ->required()
                            ->default('draft'),
                        Forms\Components\TextInput::make('category')
                            ->label(__('filament-surveys.fields.category'))
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label(__('filament-surveys.fields.type'))
                            ->options([
                                'pre' => __('filament-surveys.type.pre'),
                                'post' => __('filament-surveys.type.post'),
                            ])
                            ->required()
                            ->default('pre'),
                        Forms\Components\Toggle::make('is_reusable')
                            ->label(__('filament-surveys.fields.is_reusable'))
                            ->default(true),
                        Forms\Components\Textarea::make('description_ar')
                            ->label(__('filament-surveys.fields.description_ar'))
                            ->rows(2),
                        Forms\Components\Textarea::make('description_en')
                            ->label(__('filament-surveys.fields.description_en'))
                            ->rows(2),
                    ])->columns(2),

                Forms\Components\Section::make(__('filament-surveys.sections.questions_heading'))
                    ->description(__('filament-surveys.sections.questions_description'))
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->label('')
                            ->relationship('questions')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label(__('filament-surveys.question_fields.type'))
                                    ->helperText(__('filament-surveys.question_fields.type_helper'))
                                    ->options(
                                        collect(QuestionType::cases())->mapWithKeys(
                                            fn (QuestionType $type) => [$type->value => self::questionTypeIcon($type) . ' ' . (app()->getLocale() === 'ar' ? $type->labelAr() : $type->labelEn())]
                                        )
                                    )
                                    ->native(false)
                                    ->live()
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('question_text_ar')
                                            ->label(__('filament-surveys.question_fields.question_text_ar'))
                                            ->placeholder('مثال: ما مدى رضاك عن الفعالية؟')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('question_text_en')
                                            ->label(__('filament-surveys.question_fields.question_text_en'))
                                            ->placeholder('e.g. How satisfied were you with the event?')
                                            ->required()
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\Repeater::make('options')
                                    ->label(__('filament-surveys.question_fields.options'))
                                    ->helperText(__('filament-surveys.question_fields.options_helper'))
                                    ->simple(
                                        Forms\Components\TextInput::make('value')
                                            ->label(__('filament-surveys.question_fields.option_value'))
                                            ->required()
                                            ->maxLength(255)
                                    )
                                    ->addActionLabel(__('filament-surveys.actions.add_option'))
                                    ->reorderable()
                                    ->defaultItems(2)
                                    ->visible(fn (callable $get) => in_array($get('type'), ['multiple_choice', 'checkbox']))
                                    ->columnSpanFull(),

                                Forms\Components\Toggle::make('is_required')
                                    ->label(__('filament-surveys.question_fields.is_required'))
                                    ->helperText(__('filament-surveys.question_fields.is_required_helper'))
                                    ->default(true)
                                    ->columnSpanFull(),

                                Forms\Components\Section::make(__('filament-surveys.sections.additional_details'))
                                    ->collapsible()
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Textarea::make('description_ar')
                                                    ->label(__('filament-surveys.question_fields.description_ar'))
                                                    ->helperText('نص إضافي يظهر تحت عنوان السؤال لتوضيحه للطالب.')
                                                    ->rows(2),
                                                Forms\Components\Textarea::make('description_en')
                                                    ->label(__('filament-surveys.question_fields.description_en'))
                                                    ->rows(2),
                                            ]),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('help_text_ar')
                                                    ->label(__('filament-surveys.question_fields.help_text_ar'))
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('help_text_en')
                                                    ->label(__('filament-surveys.question_fields.help_text_en'))
                                                    ->maxLength(255),
                                            ]),
                                        Forms\Components\TextInput::make('score')
                                            ->label(__('filament-surveys.question_fields.score'))
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
                                    ->modalHeading(__('filament-surveys.actions.delete_question_heading'))
                                    ->modalDescription(__('filament-surveys.actions.delete_question_description'))
                                    ->modalSubmitActionLabel(__('filament-surveys.actions.delete_question_confirm'))
                            )
                            ->addActionLabel(__('filament-surveys.actions.add_question'))
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
            : "{$icon} " . __('filament-surveys.question_fields.new_question_label');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('filament-surveys.table.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->label(__('filament-surveys.fields.version'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament-surveys.table.type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pre' => 'info',
                        'post' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pre' => __('filament-surveys.table.type_pre'),
                        'post' => __('filament-surveys.table.type_post'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-surveys.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'archived' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\ToggleColumn::make('is_reusable')
                    ->label(__('filament-surveys.fields.is_reusable')),
                Tables\Columns\TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('عدد الأسئلة'),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label(__('filament-surveys.actions.preview'))
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (SurveyTemplate $record) => static::getUrl('preview', ['record' => $record])),

                Tables\Actions\Action::make('clone')
                    ->label(__('filament-surveys.actions.clone'))
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
