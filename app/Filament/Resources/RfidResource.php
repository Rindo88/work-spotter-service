<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RfidResource\Pages;
use App\Models\Rfid;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RfidResource extends Resource
{
    protected static ?string $model = Rfid::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'RFID Tag';

    protected static ?string $pluralModelLabel = 'RFID Tags';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('RFID Tag Information')
                    ->schema([
                        Forms\Components\TextInput::make('uid')
                            ->label('UID')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g., 04:52:7E:2A:3B:80:00')
                            ->helperText('Unique identifier for the RFID tag'),
                        
                        Forms\Components\Select::make('vendor_id')
                            ->label('Vendor')
                            ->options(Vendor::with('user')->get()->pluck('business_name', 'id'))
                            ->searchable()
                            ->required()
                            ->helperText('Select the vendor this RFID tag belongs to'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Whether this RFID tag is currently active'),
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->placeholder('e.g., Main entrance tag for ABC Store')
                            ->helperText('Description of the RFID tag purpose or location'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uid')
                    ->label('UID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('UID copied to clipboard')
                    ->copyMessageDuration(1500),
                
                Tables\Columns\TextColumn::make('vendor.business_name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('vendor.user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                
                Tables\Columns\TextColumn::make('checkins_count')
                    ->label('Check-ins')
                    ->counts('checkins')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vendor_id')
                    ->label('Vendor')
                    ->options(Vendor::with('user')->get()->pluck('business_name', 'id'))
                    ->searchable(),
                
                Tables\Filters\Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Active Tags'),
                
                Tables\Filters\Filter::make('inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false))
                    ->label('Inactive Tags'),
                
                Tables\Filters\Filter::make('unused')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('checkins'))
                    ->label('Unused Tags'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn (Rfid $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn (Rfid $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Rfid $record): string => $record->is_active ? 'danger' : 'success')
                    ->action(function (Rfid $record): void {
                        $record->update(['is_active' => !$record->is_active]);
                    })
                    ->requiresConfirmation()
                    ->modalDescription(fn (Rfid $record): string => 
                        $record->is_active 
                            ? 'Are you sure you want to deactivate this RFID tag?' 
                            : 'Are you sure you want to activate this RFID tag?'
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => true]);
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => false]);
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
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
            'index' => Pages\ListRfids::route('/'),
            'create' => Pages\CreateRfid::route('/create'),
            'view' => Pages\ViewRfid::route('/{record}'),
            'edit' => Pages\EditRfid::route('/{record}/edit'),
        ];
    }
}