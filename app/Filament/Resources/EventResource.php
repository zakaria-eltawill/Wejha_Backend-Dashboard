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
        return 'إدارة الفعاليات / Events';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('معلومات الفعالية / General Info')
                        ->schema([
                            Forms\Components\TextInput::make('title_ar')
                                ->label('العنوان (بالعربية) / Title (Arabic)')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('title_en')
                                ->label('العنوان (بالإنجليزية) / Title (English)')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('description_ar')
                                ->label('الوصف (بالعربية) / Description (Arabic)')
                                ->rows(3),
                            Forms\Components\Textarea::make('description_en')
                                ->label('الوصف (بالإنجليزية) / Description (English)')
                                ->rows(3),
                            Forms\Components\Select::make('type')
                                ->label('نوع الفعالية / Type')
                                ->options([
                                    'seminar' => 'ندوة / Seminar',
                                    'workshop' => 'ورشة عمل / Workshop',
                                    'exhibition' => 'معرض / Exhibition',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('speaker')
                                ->label('المتحدث / Speaker')
                                ->maxLength(255),
                            Forms\Components\Select::make('status')
                                ->label('الحالة / Status')
                                ->options([
                                    'draft' => 'مسودة / Draft',
                                    'published' => 'منشور / Published',
                                    'archived' => 'مؤرشف / Archived',
                                ])
                                ->required()
                                ->default('draft'),
                            Forms\Components\Select::make('visibility')
                                ->label('الظهور / Visibility')
                                ->options([
                                    'public' => 'عام / Public',
                                    'private' => 'خاص / Private',
                                ])
                                ->required()
                                ->default('public'),
                            Forms\Components\Toggle::make('featured')
                                ->label('مميز (تثبيت في البداية) / Featured')
                                ->default(false),
                            Forms\Components\FileUpload::make('banner_image')
                                ->label('صورة البانر / Banner Image')
                                ->image()
                                ->directory('banners'),
                            Forms\Components\FileUpload::make('cover_image')
                                ->label('صورة الغلاف / Cover Image')
                                ->image()
                                ->directory('covers'),
                        ])->columns(2),

                    Wizard\Step::make('التفاصيل اللوجستية / Logistics')
                        ->schema([
                            Forms\Components\DatePicker::make('event_date')
                                ->label('تاريخ الفعالية / Date')
                                ->required(),
                            Forms\Components\TextInput::make('event_time')
                                ->label('وقت الفعالية / Time')
                                ->placeholder('HH:MM')
                                ->required(),
                            Forms\Components\TextInput::make('venue')
                                ->label('الموقع / Venue')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('venue_map_url')
                                ->label('رابط خريطة الموقع / Map URL')
                                ->url()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('capacity')
                                ->label('السعة الاستيعابية / Capacity')
                                ->numeric()
                                ->required()
                                ->default(100),
                        ])->columns(2),

                    Wizard\Step::make('التسجيل والحضور / Registration')
                        ->schema([
                            Forms\Components\DateTimePicker::make('registration_opens_at')
                                ->label('تاريخ فتح التسجيل / Registration Opens'),
                            Forms\Components\DateTimePicker::make('registration_closes_at')
                                ->label('تاريخ إغلاق التسجيل / Registration Closes'),
                            Forms\Components\Toggle::make('qr_attendance_enabled')
                                ->label('تفعيل تحضير QR / QR Check-In')
                                ->default(true),
                            Forms\Components\Toggle::make('requires_approval')
                                ->label('يتطلب موافقة للتسجيل / Requires Approval')
                                ->default(false),
                            Forms\Components\TextInput::make('contact_person')
                                ->label('مسؤول التواصل / Contact Person')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('organizer_notes')
                                ->label('ملاحظات المنظمين / Organizer Notes')
                                ->rows(3),
                        ])->columns(2),

                    Wizard\Step::make('الاستبيانات / Surveys')
                        ->schema([
                            Forms\Components\Select::make('pre_survey_template_id')
                                ->label('اختر الاستبيان القبلي / Pre-Survey')
                                ->helperText('يظهر هذا الاستبيان للطالب عند التسجيل في الفعالية. اتركه فارغًا إن لم ترغب باستبيان قبلي. / Shown to the student when they register. Leave empty for none.')
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
                                ->label('اختر الاستبيان البعدي / Post-Survey')
                                ->helperText('يظهر هذا الاستبيان للطالب بعد تسجيل حضوره في الفعالية. اتركه فارغًا إن لم ترغب باستبيان بعدي. / Shown to the student after they check in. Leave empty for none.')
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
                    ->label('عنوان الفعالية بالعربية / Title (Arabic)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع / Type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('التاريخ / Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue')
                    ->label('الموقع / Venue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('السعة / Capacity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة / Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\ToggleColumn::make('featured')
                    ->label('مثبتة / Featured'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'seminar' => 'ندوة / Seminar',
                        'workshop' => 'ورشة عمل / Workshop',
                        'exhibition' => 'معرض / Exhibition',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'مسودة / Draft',
                        'published' => 'منشور / Published',
                        'archived' => 'مؤرشف / Archived',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('scan')
                    ->label('مسح / Scan')
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
