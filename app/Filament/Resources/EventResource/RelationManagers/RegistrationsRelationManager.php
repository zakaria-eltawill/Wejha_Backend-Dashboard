<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use App\Services\AttendanceService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'التسجيلات والحضور / Registrations & Attendance';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'قيد الانتظار / Pending',
                        'approved' => 'مقبول / Approved',
                        'rejected' => 'مرفوض / Rejected',
                        'cancelled' => 'ملغي / Cancelled',
                        'checked_in' => 'تم تسجيل الحضور / Checked In',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Select::make('source')
                    ->options([
                        'web' => 'الموقع الإلكتروني / Web',
                        'mobile' => 'تطبيق الجوال / Mobile',
                        'admin' => 'لوحة التحكم / Admin Panel',
                    ])
                    ->required()
                    ->default('admin'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                 Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المشارك / Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('البريد الإلكتروني / Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone_number')
                    ->label('رقم الجوال / Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.school_name')
                    ->label('المدرسة / School')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.academic_year')
                    ->label('السنة الدراسية / Year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة / Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'checked_in' => 'success',
                        'rejected', 'cancelled' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('source')
                    ->label('المصدر / Source'),
                Tables\Columns\TextColumn::make('attendance.scan_time')
                    ->label('وقت التحضير / Check-In Time')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'checked_in' => 'Checked In',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('قبول / Approve')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn (Registration $record): bool => $record->status === RegistrationStatus::PENDING)
                    ->action(fn (Registration $record) => $record->update(['status' => RegistrationStatus::APPROVED])),

                Tables\Actions\Action::make('reject')
                    ->label('رفض / Reject')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->visible(fn (Registration $record): bool => $record->status === RegistrationStatus::PENDING)
                    ->action(fn (Registration $record) => $record->update(['status' => RegistrationStatus::REJECTED])),

                Tables\Actions\Action::make('checkin')
                    ->label('تحضير / Check In')
                    ->icon('heroicon-m-qr-code')
                    ->color('success')
                    ->visible(fn (Registration $record): bool => $record->status === RegistrationStatus::APPROVED)
                    ->action(function (Registration $record) {
                        app(AttendanceService::class)->recordAttendance(
                            $record->qr_hash,
                            auth()->id(),
                            'Admin Panel',
                            request()->ip()
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
}
