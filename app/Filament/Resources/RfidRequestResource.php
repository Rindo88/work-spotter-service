<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RfidRequestResource\Pages;
use App\Models\RfidRequest;
use App\Models\Vendor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class RfidRequestResource extends Resource
{
    protected static ?string $model = RfidRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'RFID Request';

    protected static ?string $pluralModelLabel = 'RFID Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Request Information')
                    ->schema([
                        Forms\Components\Select::make('vendor_id')
                            ->label('Vendor')
                            ->options(Vendor::with('user')->get()->pluck('business_name', 'id'))
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $context): bool => $context === 'edit'),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                            ])
                            ->default('pending')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if (in_array($state, ['approved', 'rejected'])) {
                                    $set('approved_by', auth()->id());
                                    $set('approved_at', now());
                                }
                            }),
                        
                        Forms\Components\Select::make('approved_by')
                            ->label('Approved By')
                            ->options(User::where('role', 'admin')->pluck('name', 'id'))
                            ->searchable()
                            ->disabled()
                            ->visible(fn (Forms\Get $get): bool => 
                                in_array($get('status'), ['approved', 'rejected', 'processing', 'shipped', 'delivered'])
                            ),
                        
                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Approved At')
                            ->disabled()
                            ->visible(fn (Forms\Get $get): bool => 
                                in_array($get('status'), ['approved', 'rejected', 'processing', 'shipped', 'delivered'])
                            ),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Tracking & Notes')
                    ->schema([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->maxLength(255)
                            ->visible(fn (Forms\Get $get): bool => 
                                in_array($get('status'), ['processing', 'shipped', 'delivered'])
                            ),
                        
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(4)
                            ->placeholder('Add notes about this request...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('vendor.business_name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('vendor.user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'processing',
                        'success' => ['shipped', 'delivered'],
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking')
                    ->placeholder('N/A')
                    ->copyable()
                    ->copyMessage('Tracking number copied!')
                    ->copyMessageDuration(1500),
                
                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->placeholder('N/A'),
                
                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved At')
                    ->dateTime()
                    ->placeholder('N/A')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ]),
                
                Tables\Filters\SelectFilter::make('vendor_id')
                    ->label('Vendor')
                    ->options(Vendor::with('user')->get()->pluck('business_name', 'id'))
                    ->searchable(),
                
                Tables\Filters\Filter::make('pending_requests')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'pending'))
                    ->label('Pending Requests'),
                
                Tables\Filters\Filter::make('approved_requests')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'approved'))
                    ->label('Approved Requests'),
                
                Tables\Filters\Filter::make('recent_requests')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7)))
                    ->label('Recent Requests (7 days)'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (RfidRequest $record): bool => $record->status === 'pending')
                    ->action(function (RfidRequest $record): void {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                        
                        Notification::make()
                            ->title('Request Approved')
                            ->body("RFID request for {$record->vendor->business_name} has been approved.")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to approve this RFID request?'),
                
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (RfidRequest $record): bool => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Rejection Reason')
                            ->required()
                            ->placeholder('Please provide a reason for rejection...')
                    ])
                    ->action(function (RfidRequest $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                            'admin_notes' => $data['admin_notes'],
                        ]);
                        
                        Notification::make()
                            ->title('Request Rejected')
                            ->body("RFID request for {$record->vendor->business_name} has been rejected.")
                            ->warning()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('mark_processing')
                    ->label('Mark as Processing')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('primary')
                    ->visible(fn (RfidRequest $record): bool => $record->status === 'approved')
                    ->action(function (RfidRequest $record): void {
                        $record->update(['status' => 'processing']);
                        
                        Notification::make()
                            ->title('Status Updated')
                            ->body("Request is now being processed.")
                            ->info()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('mark_shipped')
                    ->label('Mark as Shipped')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (RfidRequest $record): bool => $record->status === 'processing')
                    ->form([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->required()
                            ->placeholder('Enter tracking number...')
                    ])
                    ->action(function (RfidRequest $record, array $data): void {
                        $record->update([
                            'status' => 'shipped',
                            'tracking_number' => $data['tracking_number'],
                        ]);
                        
                        Notification::make()
                            ->title('Request Shipped')
                            ->body("RFID has been shipped with tracking number: {$data['tracking_number']}")
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('mark_delivered')
                    ->label('Mark as Delivered')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (RfidRequest $record): bool => $record->status === 'shipped')
                    ->action(function (RfidRequest $record): void {
                        $record->update(['status' => 'delivered']);
                        
                        Notification::make()
                            ->title('Request Completed')
                            ->body("RFID has been successfully delivered.")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Confirm that the RFID has been delivered to the vendor?'),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records): void {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'approved',
                                        'approved_by' => auth()->id(),
                                        'approved_at' => now(),
                                    ]);
                                    $count++;
                                }
                            }
                            
                            Notification::make()
                                ->title('Bulk Approval Complete')
                                ->body("{$count} requests have been approved.")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to approve all selected pending requests?'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRfidRequests::route('/'),
            'create' => Pages\CreateRfidRequest::route('/create'),
            'view' => Pages\ViewRfidRequest::route('/{record}'),
            'edit' => Pages\EditRfidRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'pending')->count();
        return $pendingCount > 0 ? 'warning' : null;
    }
}