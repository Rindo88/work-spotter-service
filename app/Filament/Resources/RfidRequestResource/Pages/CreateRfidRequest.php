<?php

namespace App\Filament\Resources\RfidRequestResource\Pages;

use App\Filament\Resources\RfidRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRfidRequest extends CreateRecord
{
    protected static string $resource = RfidRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default status to pending if not set
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        return $data;
    }
}