<?php

namespace App\Filament\Resources\RfidRequestResource\Pages;

use App\Filament\Resources\RfidRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRfidRequest extends EditRecord
{
    protected static string $resource = RfidRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('approve')
                ->label('Approve Request')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Approve RFID Request')
                ->modalDescription('Are you sure you want to approve this RFID request?')
                ->action(function () {
                    $this->record->update([
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Request approved successfully')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'approved_by', 'approved_at']);
                }),
            Actions\Action::make('reject')
                ->label('Reject Request')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Reject RFID Request')
                ->modalDescription('Are you sure you want to reject this RFID request?')
                ->action(function () {
                    $this->record->update([
                        'status' => 'rejected',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Request rejected')
                        ->warning()
                        ->send();

                    $this->refreshFormData(['status', 'approved_by', 'approved_at']);
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If status is being changed to approved or rejected, set approved_by and approved_at
        if (in_array($data['status'], ['approved', 'rejected']) && 
            $this->record->status === 'pending') {
            $data['approved_by'] = auth()->id();
            $data['approved_at'] = now();
        }

        return $data;
    }
}