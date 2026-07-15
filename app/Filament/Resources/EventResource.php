<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Models\Event;
use App\Models\EventEvaluation;
use App\Models\SurveyTemplate;
use App\Enums\EventType;
use App\Enums\EventStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('filament-events.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-events.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-events.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('filament-events.steps.general_info'))
                        ->schema([
                            Forms\Components\TextInput::make('title_ar')
                                ->label(__('filament-events.fields.title_ar'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('title_en')
                                ->label(__('filament-events.fields.title_en'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('description_ar')
                                ->label(__('filament-events.fields.description_ar'))
                                ->rows(3),
                            Forms\Components\Textarea::make('description_en')
                                ->label(__('filament-events.fields.description_en'))
                                ->rows(3),
                            Forms\Components\Select::make('type')
                                ->label(__('filament-events.fields.type'))
                                ->options([
                                    'seminar' => __('filament-events.type.seminar'),
                                    'workshop' => __('filament-events.type.workshop'),
                                    'exhibition' => __('filament-events.type.exhibition'),
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('speaker')
                                ->label(__('filament-events.fields.speaker'))
                                ->maxLength(255),
                            Forms\Components\Select::make('status')
                                ->label(__('filament-events.fields.status'))
                                ->options([
                                    'draft' => __('filament-events.event_status.draft'),
                                    'published' => __('filament-events.event_status.published'),
                                    'archived' => __('filament-events.event_status.archived'),
                                ])
                                ->required()
                                ->default('draft'),
                            Forms\Components\Select::make('visibility')
                                ->label(__('filament-events.fields.visibility'))
                                ->options([
                                    'public' => __('filament-events.visibility.public'),
                                    'private' => __('filament-events.visibility.private'),
                                ])
                                ->required()
                                ->default('public'),
                            Forms\Components\Toggle::make('featured')
                                ->label(__('filament-events.fields.featured'))
                                ->default(false),
                            Forms\Components\FileUpload::make('banner_image')
                                ->label(__('filament-events.fields.banner_image'))
                                ->image()
                                ->directory('banners'),
                            Forms\Components\FileUpload::make('cover_image')
                                ->label(__('filament-events.fields.cover_image'))
                                ->image()
                                ->directory('covers'),
                        ])->columns(2),

                    Wizard\Step::make(__('filament-events.steps.logistics'))
                        ->schema([
                            Forms\Components\DatePicker::make('event_date')
                                ->label(__('filament-events.fields.event_date'))
                                ->required(),
                            Forms\Components\TextInput::make('event_time')
                                ->label(__('filament-events.fields.event_time'))
                                ->placeholder('HH:MM')
                                ->required(),
                            Forms\Components\TextInput::make('venue')
                                ->label(__('filament-events.fields.venue'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('venue_map_url')
                                ->label(__('filament-events.fields.venue_map_url'))
                                ->url()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('capacity')
                                ->label(__('filament-events.fields.capacity'))
                                ->numeric()
                                ->required()
                                ->default(100),
                        ])->columns(2),

                    Wizard\Step::make(__('filament-events.steps.registration'))
                        ->schema([
                            Forms\Components\DateTimePicker::make('registration_opens_at')
                                ->label(__('filament-events.fields.registration_opens_at')),
                            Forms\Components\DateTimePicker::make('registration_closes_at')
                                ->label(__('filament-events.fields.registration_closes_at')),
                            Forms\Components\Toggle::make('qr_attendance_enabled')
                                ->label(__('filament-events.fields.qr_attendance_enabled'))
                                ->default(true),
                            Forms\Components\Toggle::make('requires_approval')
                                ->label(__('filament-events.fields.requires_approval'))
                                ->default(false),
                            Forms\Components\TextInput::make('contact_person')
                                ->label(__('filament-events.fields.contact_person'))
                                ->maxLength(255),
                            Forms\Components\Textarea::make('organizer_notes')
                                ->label(__('filament-events.fields.organizer_notes'))
                                ->rows(3),
                        ])->columns(2),

                    Wizard\Step::make(__('filament-events.steps.surveys'))
                        ->schema([
                            Forms\Components\Select::make('pre_survey_template_id')
                                ->label(__('filament-events.fields.pre_survey_template_id'))
                                ->helperText(__('filament-events.helper_texts.pre_survey'))
                                ->options(fn () => SurveyTemplate::where('type', 'pre')->pluck('name_ar', 'id'))
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->dehydrated(false)
                                ->afterStateHydrated(function (Forms\Components\Select $component, $record) {
                                    if ($record) {
                                        $component->state(
                                            $record->evaluations()->where('evaluation_type', 'pre')->value('survey_template_id')
                                        );
                                    }
                                }),
                            Forms\Components\Select::make('post_survey_template_id')
                                ->label(__('filament-events.fields.post_survey_template_id'))
                                ->helperText(__('filament-events.helper_texts.post_survey'))
                                ->options(fn () => SurveyTemplate::where('type', 'post')->pluck('name_ar', 'id'))
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->dehydrated(false)
                                ->afterStateHydrated(function (Forms\Components\Select $component, $record) {
                                    if ($record) {
                                        $component->state(
                                            $record->evaluations()->where('evaluation_type', 'post')->value('survey_template_id')
                                        );
                                    }
                                }),
                        ])->columns(2),
                ])->columnSpanFull()
            ]);
    }

    /**
     * Syncs the pre/post survey Select values from the Event form (which are virtual,
     * dehydrated(false) fields) into the event_evaluations join table, since Event has
     * no pre_survey_id/post_survey_id columns of its own.
     */
    public static function syncSurveyEvaluations(Event $event, array $data): void
    {
        foreach (['pre', 'post'] as $type) {
            $templateId = $data["{$type}_survey_template_id"] ?? null;

            if ($templateId) {
                EventEvaluation::updateOrCreate(
                    ['event_id' => $event->id, 'evaluation_type' => $type],
                    ['survey_template_id' => $templateId, 'is_active' => true]
                );
            } else {
                EventEvaluation::where('event_id', $event->id)
                    ->where('evaluation_type', $type)
                    ->delete();
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_ar')
                    ->label(__('filament-events.table.columns.title_ar'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament-events.table.columns.type'))
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('event_date')
                    ->label(__('filament-events.table.columns.event_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue')
                    ->label(__('filament-events.fields.venue'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label(__('filament-events.table.columns.capacity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-events.fields.status'))
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\ToggleColumn::make('featured')
                    ->label(__('filament-events.table.columns.featured')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'seminar' => __('filament-events.type.seminar'),
                        'workshop' => __('filament-events.type.workshop'),
                        'exhibition' => __('filament-events.type.exhibition'),
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => __('filament-events.event_status.draft'),
                        'published' => __('filament-events.event_status.published'),
                        'archived' => __('filament-events.event_status.archived'),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('scan')
                    ->label(__('filament-events.actions.scan'))
                    ->icon('heroicon-m-qr-code')
                    ->color('warning')
                    ->url(fn (Event $record) => static::getUrl('scan', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\EventResource\RelationManagers\RegistrationsRelationManager::class,
            \App\Filament\Resources\EventResource\RelationManagers\SurveyResponsesRelationManager::class,
            \App\Filament\Resources\EventResource\RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\EventResource\Pages\ListEvents::route('/'),
            'create' => \App\Filament\Resources\EventResource\Pages\CreateEvent::route('/create'),
            'edit' => \App\Filament\Resources\EventResource\Pages\EditEvent::route('/{record}/edit'),
            'scan' => \App\Filament\Resources\EventResource\Pages\ScanEvent::route('/{record}/scan'),
        ];
    }
}
