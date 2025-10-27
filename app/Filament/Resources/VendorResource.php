<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->options(User::where('role', 'vendor')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        
                        Forms\Components\TextInput::make('business_name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(Category::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        
                        Forms\Components\Select::make('type')
                            ->options([
                                'formal' => 'Formal',
                                'informal' => 'Informal',
                            ])
                            ->default('informal')
                            ->required(),
                        
                        Forms\Components\Toggle::make('is_rfid')
                            ->label('RFID Enabled')
                            ->default(false),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Location Information')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.0000001)
                            ->placeholder('e.g., -6.2087634'),
                        
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.0000001)
                            ->placeholder('e.g., 106.8456'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_picture')
                            ->image()
                            ->directory('vendor-profiles')
                            ->visibility('public'),
                        
                        Forms\Components\TextInput::make('rating_avg')
                            ->label('Average Rating')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0)
                            ->maxValue(5)
                            ->default(0),
                        
                        Forms\Components\TextInput::make('operational_notes')
                            ->maxLength(255)
                            ->placeholder('e.g., Open 24/7, Closed on Sundays'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-vendor.png')),
                
                Tables\Columns\TextColumn::make('business_name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'formal',
                        'warning' => 'informal',
                    ]),
                
                Tables\Columns\IconColumn::make('is_rfid')
                    ->label('RFID')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                
                Tables\Columns\TextColumn::make('rating_avg')
                    ->label('Rating')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('address')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id')),
                
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'formal' => 'Formal',
                        'informal' => 'Informal',
                    ]),
                
                Tables\Filters\Filter::make('rfid_enabled')
                    ->query(fn (Builder $query): Builder => $query->where('is_rfid', true))
                    ->label('RFID Enabled'),
                
                Tables\Filters\Filter::make('high_rated')
                    ->query(fn (Builder $query): Builder => $query->where('rating_avg', '>=', 4))
                    ->label('High Rated (4+ stars)'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'view' => Pages\ViewVendor::route('/{record}'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}