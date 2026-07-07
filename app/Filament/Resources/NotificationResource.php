<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\Communications;
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
    protected static ?string $cluster = Communications::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function getNavigationLabel(): string
    {
        return 'الإشعارات والتعميمات / Send Alerts';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title_ar')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title_en')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('content_ar')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('content_en')
                            ->required()
                            ->rows(3),
                        Forms\Components\Select::make('recipient_type')
                            ->options([
                                'all' => 'الكل / All Active Users',
                                'individual' => 'مستخدم محدد / Specific User',
                                'role' => 'مجموعة صلاحية / Specific Role Group',
                                'event' => 'المسجلون في فعالية / Event Attendees',
                            ])
                            ->reactive()
                            ->required()
                            ->default('all'),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->visible(fn (callable $get) => $get('recipient_type') === 'individual')
                            ->required(fn (callable $get) => $get('recipient_type') === 'individual'),
                        Forms\Components\Select::make('role_id')
                            ->relationship('role', 'name')
                            ->visible(fn (callable $get) => $get('recipient_type') === 'role')
                            ->required(fn (callable $get) => $get('recipient_type') === 'role'),
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'title_ar')
                            ->visible(fn (callable $get) => $get('recipient_type') === 'event')
                            ->required(fn (callable $get) => $get('recipient_type') === 'event'),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->placeholder('اتركه فارغاً للإرسال الفوري'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'مسودة / Draft',
                                'scheduled' => 'مجدول / Scheduled',
                                'processing' => 'جارِ الإرسال / Processing',
                                'sent' => 'تم الإرسال / Sent',
                                'failed' => 'فشل / Failed',
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recipient_type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status')
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
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('إرسال الآن / Send Now')
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
