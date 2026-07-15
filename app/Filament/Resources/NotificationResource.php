<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Models\Notification;
use App\Services\NotificationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('filament-notifications-audit.notifications.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-notifications-audit.notifications.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-notifications-audit.notifications.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title_ar')
                            ->label(__('filament-notifications-audit.notifications.fields.title_ar'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title_en')
                            ->label(__('filament-notifications-audit.notifications.fields.title_en'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('content_ar')
                            ->label(__('filament-notifications-audit.notifications.fields.content_ar'))
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('content_en')
                            ->label(__('filament-notifications-audit.notifications.fields.content_en'))
                            ->required()
                            ->rows(3),
                        Forms\Components\Select::make('recipient_type')
                            ->label(__('filament-notifications-audit.notifications.fields.recipient_type'))
                            ->options([
                                'all' => __('filament-notifications-audit.notifications.recipient_type.all'),
                                'individual' => __('filament-notifications-audit.notifications.recipient_type.individual'),
                                'role' => __('filament-notifications-audit.notifications.recipient_type.role'),
                                'event' => __('filament-notifications-audit.notifications.recipient_type.event'),
                            ])
                            ->reactive()
                            ->required()
                            ->default('all'),
                        Forms\Components\Select::make('user_id')
                            ->label(__('filament-notifications-audit.notifications.fields.user_id'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->visible(fn (callable $get) => $get('recipient_type') === 'individual')
                            ->required(fn (callable $get) => $get('recipient_type') === 'individual'),
                        Forms\Components\Select::make('role_id')
                            ->label(__('filament-notifications-audit.notifications.fields.role_id'))
                            ->relationship('role', 'name')
                            ->visible(fn (callable $get) => $get('recipient_type') === 'role')
                            ->required(fn (callable $get) => $get('recipient_type') === 'role'),
                        Forms\Components\Select::make('event_id')
                            ->label(__('filament-notifications-audit.notifications.fields.event_id'))
                            ->relationship('event', 'title_ar')
                            ->visible(fn (callable $get) => $get('recipient_type') === 'event')
                            ->required(fn (callable $get) => $get('recipient_type') === 'event'),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label(__('filament-notifications-audit.notifications.fields.scheduled_at'))
                            ->placeholder(__('filament-notifications-audit.notifications.fields.scheduled_at_placeholder')),
                        Forms\Components\Select::make('status')
                            ->label(__('filament-notifications-audit.notifications.fields.status'))
                            ->options([
                                'draft' => __('filament-notifications-audit.notifications.status.draft'),
                                'scheduled' => __('filament-notifications-audit.notifications.status.scheduled'),
                                'processing' => __('filament-notifications-audit.notifications.status.processing'),
                                'sent' => __('filament-notifications-audit.notifications.status.sent'),
                                'failed' => __('filament-notifications-audit.notifications.status.failed'),
                            ])
                            ->required()
                            ->default('draft')
                            ->disabled(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_ar')
                    ->label(__('filament-notifications-audit.notifications.table.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recipient_type')
                    ->label(__('filament-notifications-audit.notifications.table.recipient_type'))
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-notifications-audit.notifications.table.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'processing' => 'info',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('filament-notifications-audit.notifications.table.scheduled_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->label(__('filament-notifications-audit.notifications.table.delivered_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label(__('filament-notifications-audit.notifications.actions.send_now'))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (Notification $record): bool => in_array($record->status, ['draft', 'failed']))
                    ->action(fn (Notification $record) => app(NotificationService::class)->sendNotification($record)),

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
            'index' => \App\Filament\Resources\NotificationResource\Pages\ListNotifications::route('/'),
            'create' => \App\Filament\Resources\NotificationResource\Pages\CreateNotification::route('/create'),
            'edit' => \App\Filament\Resources\NotificationResource\Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
