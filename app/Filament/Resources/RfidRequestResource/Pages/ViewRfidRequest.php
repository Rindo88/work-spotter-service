<?php

namespace App\Filament\Resources\RfidRequestResource\Pages;

use App\Filament\Resources\RfidRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewRfidRequest extends ViewRecord
{
    protected static string $resource = RfidRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
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
                }),
            Actions\Action::make('mark_processing')
                ->label('Mark as Processing')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('primary')
                ->visible(fn () => $this->record->status === 'approved')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'processing']);

                    Notification::make()
                        ->title('Request marked as processing')
                        ->info()
                        ->send();
                }),
            Actions\Action::make('mark_shipped')
                ->label('Mark as Shipped')
                ->icon('heroicon-o-truck')
                ->color('warning')
                ->visible(fn () => $this->record->status === 'processing')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'shipped']);

                    Notification::make()
                        ->title('Request marked as shipped')
                        ->info()
                        ->send();
                }),
            Actions\Action::make('mark_delivered')
                ->label('Mark as Delivered')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status === 'shipped')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'delivered']);

                    Notification::make()
                        ->title('Request marked as delivered')
                        ->success()
                        ->send();
                }),
        ];
    }
}