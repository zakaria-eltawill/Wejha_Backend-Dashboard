<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\EventManagement;
use App\Models\Attendance;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $cluster = EventManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    public static function getNavigationLabel(): string
    {
        return 'سجل الحضور / Attendance Log';
    }

    public static function getModelLabel(): string
    {
        return 'تحضير / Attendance';
    }

    public static function getPluralModelLabel(): string
    {
        return 'سجل الحضور / Attendance Log';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('registration_id')
                            ->label('التسجيل / Registration')
                            ->relationship('registration', 'id')
                            ->getOptionLabelFromRecordUsing(fn (Registration $record) => "{$record->user->name} - {$record->event->title_ar}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('scanner_user_id')
                            ->label('المنظم المسؤول / Operator')
                            ->relationship('scannerUser', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DateTimePicker::make('scan_time')
                            ->label('وقت التحضير / Check-In Time')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('device')
                            ->label('جهاز التحضير / Device')
                            ->maxLength(255)
                            ->default('لوحة التحكم / Admin Panel'),
                        Forms\Components\TextInput::make('ip_address')
                            ->label('عنوان IP / IP Address')
                            ->maxLength(45),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration.user.name')
                    ->label('اسم الطالب / Student Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.user.email')
                    ->label('البريد الإلكتروني / Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('registration.user.phone_number')
                    ->label('رقم الجوال / Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration.user.school_name')
                    ->label('المدرسة / School')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.event.title_ar')
                    ->label('الفعالية / Event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scan_time')
                    ->label('وقت التحضير / Check-In Time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('device')
                    ->label('جهاز التحضير / Device')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('scannerUser.name')
                    ->label('المنظم المسؤول / Operator')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('الفعالية / Event')
                    ->relationship('registration.event', 'title_ar'),
                Tables\Filters\Filter::make('scan_time')
                    ->label('تاريخ اليوم / Today')
                    ->query(fn ($query) => $query->whereDate('scan_time', today())),
            ])
            ->actions([
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\AttendanceResource\Pages\ListAttendances::route('/'),
            'create' => \App\Filament\Resources\AttendanceResource\Pages\CreateAttendance::route('/create'),
            'edit' => \App\Filament\Resources\AttendanceResource\Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
