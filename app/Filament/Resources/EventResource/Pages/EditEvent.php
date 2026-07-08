<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('scan')
                ->label('مسح التذاكر / Scan QR')
                ->icon('heroicon-m-qr-code')
                ->color('warning')
                ->url(fn (): string => static::getResource()::getUrl('scan', ['record' => $this->getRecord()])),
            Actions\DeleteAction::make(),
        ];
    }
}
