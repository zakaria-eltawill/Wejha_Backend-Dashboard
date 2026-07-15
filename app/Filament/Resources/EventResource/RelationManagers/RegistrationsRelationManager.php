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
        return __('filament-events.relation_managers.registrations.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('filament-events.fields.name')),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique('users', 'email')
                            ->maxLength(255)
                            ->label(__('filament-events.fields.email')),
                        Forms\Components\TextInput::make('phone_number')
                            ->maxLength(20)
                            ->label(__('filament-events.fields.phone')),
                        Forms\Components\TextInput::make('school_name')
                            ->maxLength(255)
                            ->label(__('filament-events.fields.school')),
                        Forms\Components\Select::make('specialization')
                            ->options([
                                'علمي' => __('filament-events.specialization.scientific'),
                                'أدبي' => __('filament-events.specialization.literary'),
                            ])
                            ->label(__('filament-events.fields.specialization')),
                        Forms\Components\TextInput::make('academic_year')
                            ->maxLength(50)
                            ->label(__('filament-events.fields.academic_year')),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => __('filament-events.gender.male'),
                                'female' => __('filament-events.gender.female'),
                            ])
                            ->label(__('filament-events.fields.gender')),
                        Forms\Components\Hidden::make('password')
                            ->default(fn () => \Illuminate\Support\Facades\Hash::make('wejha2026password')),
                        Forms\Components\Hidden::make('status')
                            ->default('active'),
                    ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => __('filament-events.registration_status.pending'),
                        'approved' => __('filament-events.registration_status.approved'),
                        'rejected' => __('filament-events.registration_status.rejected'),
                        'cancelled' => __('filament-events.registration_status.cancelled'),
                        'checked_in' => __('filament-events.registration_status.checked_in'),
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Select::make('source')
                    ->options([
                        'web' => __('filament-events.source.web'),
                        'mobile' => __('filament-events.source.mobile'),
                        'admin' => __('filament-events.source.admin'),
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
                    ->label(__('filament-events.table.columns.participant_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament-events.fields.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone_number')
                    ->label(__('filament-events.fields.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.school_name')
                    ->label(__('filament-events.fields.school'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.specialization')
                    ->label(__('filament-events.table.columns.specialization'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.academic_year')
                    ->label(__('filament-events.fields.academic_year'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-events.fields.status'))
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'checked_in' => 'success',
                        'rejected', 'cancelled' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('source')
                    ->label(__('filament-events.table.columns.source')),
                Tables\Columns\TextColumn::make('attendance.scan_time')
                    ->label(__('filament-events.table.columns.checkin_time'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => __('filament-events.registration_status.pending'),
                        'approved' => __('filament-events.registration_status.approved'),
                        'checked_in' => __('filament-events.registration_status.checked_in'),
                        'rejected' => __('filament-events.registration_status.rejected'),
                        'cancelled' => __('filament-events.registration_status.cancelled'),
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label(__('filament-events.actions.approve'))
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn (Registration $record): bool => $record->status === RegistrationStatus::PENDING)
                    ->action(fn (Registration $record) => $record->update(['status' => RegistrationStatus::APPROVED])),

                Tables\Actions\Action::make('reject')
                    ->label(__('filament-events.actions.reject'))
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->visible(fn (Registration $record): bool => $record->status === RegistrationStatus::PENDING)
                    ->action(fn (Registration $record) => $record->update(['status' => RegistrationStatus::REJECTED])),

                Tables\Actions\Action::make('checkin')
                    ->label(__('filament-events.actions.checkin'))
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
