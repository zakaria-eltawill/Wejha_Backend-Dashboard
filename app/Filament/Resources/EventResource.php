<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\EventManagement;
use App\Models\Event;
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
    protected static ?string $cluster = EventManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    public static function getNavigationLabel(): string
    {
        return 'الفعاليات / Events';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('معلومات الفعالية / General Info')
                        ->schema([
                            Forms\Components\TextInput::make('title_ar')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('title_en')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('description_ar')
                                ->rows(3),
                            Forms\Components\Textarea::make('description_en')
                                ->rows(3),
                            Forms\Components\Select::make('type')
                                ->options([
                                    'seminar' => 'ندوة / Seminar',
                                    'workshop' => 'ورشة عمل / Workshop',
                                    'exhibition' => 'معرض / Exhibition',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('speaker')
                                ->maxLength(255),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'مسودة / Draft',
                                    'published' => 'منشور / Published',
                                    'archived' => 'مؤرشف / Archived',
                                ])
                                ->required()
                                ->default('draft'),
                            Forms\Components\Select::make('visibility')
                                ->options([
                                    'public' => 'عام / Public',
                                    'private' => 'خاص / Private',
                                ])
                                ->required()
                                ->default('public'),
                            Forms\Components\Toggle::make('featured')
                                ->default(false),
                            Forms\Components\FileUpload::make('banner_image')
                                ->image()
                                ->directory('banners'),
                            Forms\Components\FileUpload::make('cover_image')
                                ->image()
                                ->directory('covers'),
                        ])->columns(2),

                    Wizard\Step::make('التفاصيل اللوجستية / Logistics')
                        ->schema([
                            Forms\Components\DatePicker::make('event_date')
                                ->required(),
                            Forms\Components\TextInput::make('event_time')
                                ->placeholder('HH:MM')
                                ->required(),
                            Forms\Components\TextInput::make('venue')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('venue_map_url')
                                ->url()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('capacity')
                                ->numeric()
                                ->required()
                                ->default(100),
                        ])->columns(2),

                    Wizard\Step::make('التسجيل والحضور / Registration')
                        ->schema([
                            Forms\Components\DateTimePicker::make('registration_opens_at'),
                            Forms\Components\DateTimePicker::make('registration_closes_at'),
                            Forms\Components\Toggle::make('qr_attendance_enabled')
                                ->default(true),
                            Forms\Components\Toggle::make('requires_approval')
                                ->default(false),
                            Forms\Components\TextInput::make('contact_person')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('organizer_notes')
                                ->rows(3),
                        ])->columns(2),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_ar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\ToggleColumn::make('featured'),
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
