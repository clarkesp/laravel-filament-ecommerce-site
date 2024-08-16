<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Order Information')->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name') // Use 'user' instead of 'customer'
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'pix' => 'PIX',
                                'boleto' => 'Boleto',
                                'creditcard' => 'Credit Card',
                                'debitcard' => 'Debit Card',
                                'stripe' => 'Stripe',
                                'paypal' => 'PayPal',
                            ])->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])->required(),
                        Forms\Components\ToggleButtons::make('payment_status')
                            ->inline()
                            ->default('pending')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])->colors([
                                'pending' => Color::Amber,
                                'paid' => Color::Green,
                                'failed' => Color::Red,
                            ])->icons([
                                'pending' => 'heroicon-m-arrow-path',
                                'paid' => 'heroicon-m-check-badge',
                                'failed' => 'heroicon-m-x-circle',
                            ]),
                        Forms\Components\ToggleButtons::make('status')
                            ->inline()
                            ->label('Delivery Status')
                            ->default('new')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])->colors([
                                'new' => Color::Indigo,
                                'processing' => Color::Amber,
                                'shipped' => Color::Amber,
                                'delivered' => Color::Green,
                                'cancelled' => Color::Red,
                            ])->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ]),
                        Forms\Components\Select::make('currency')
                                ->options([
                                    'usd' => 'USD',
                                    'brl' => 'BRL',
                                    'auz' => 'AUZ',
                                    'eur' => 'EUR',
                                    'cad' => 'CAD',
                                    'ukr' => 'UKR',
                                ]),
                        Forms\Components\Select::make('delivery_method')
                                ->options([
                                    'instoresale' => 'In store sale',
                                    'pickup' => 'Pick Up',
                                    'bymail' => 'By Mail',
                                    'bycourier' => 'By courier',
                                    'airmail' => 'Airmail',
                                ]),

                        Forms\Components\Textarea::make('delivery_amount')
                            ->default(0),
                        Forms\Components\MarkdownEditor::make('note')
                            ->columnSpanFull(),
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_method')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
