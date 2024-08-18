<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\Store;
use App\Models\Store\Order;
use App\Models\Store\Product;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->default('Choose Client or Customer')
                            ->relationship('user', 'name') // Use 'user' instead of 'customer'
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'pix' => 'PIX',
                                'cash' => 'Cash',
                                'boleto' => 'Boleto',
                                'creditcard' => 'Credit Card',
                                'debitcard' => 'Debit Card',
                                'stripe' => 'Stripe',
                                'paypal' => 'PayPal',
                            ])
                            ->default('pix')
                            ->required(),
                        ToggleButtons::make('payment_status')
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
                        ToggleButtons::make('status')
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
                        Select::make('currency')
                                ->options([
                                    'usd' => 'USD',
                                    'brl' => 'BRL',
                                    'auz' => 'AUZ',
                                    'eur' => 'EUR',
                                    'cad' => 'CAD',
                                    'ukr' => 'UKR',
                                ])
                                ->default('brl'),
                        Select::make('delivery_method')
                                ->options([
                                    'instore' => 'In store sale',
                                    'pickup' => 'Pick Up',
                                    'bymail' => 'By Mail',
                                    'bycourier' => 'By courier',
                                    'airmail' => 'Airmail',
                                ])
                                ->default('instore'),

                        Textarea::make('delivery_amount')
                            ->default(0),
                        RichEditor::make('note')->toolbarButtons([
                            'blockquote', 'bold', 'bulletList', 'codeBlock', 'heading', 'italic', 'link',
                            'orderedList', 'table', 'blockquote', 'bold', 'h2', 'h3', 'italic', 'link', 'underline'])
                            ->label('Notes about the purchase')
                            ->columnSpanFull(),

                    // repeater section
                        Section::make('order items')->schema([
                            Repeater::make('items')
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->distinct()
                                        ->columnSpan(5)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $product = Product::find($state);
                                            $unitAmount = $product?->price ?? 0;
                                            $set('unit_amount', $unitAmount);
                                            $quantity = $get('quantity') ?? 1;
                                            $set('total_amount', $unitAmount * $quantity);
                                        }),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->minValue(1)
                                        ->columnSpan(1)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $unitAmount = $get('unit_amount') ?? 0;
                                            $set('total_amount', $unitAmount * $state);
                                        }),
                                    TextInput::make('unit_amount')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->disabled()
                                        ->columnSpan(3),
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->columnSpan(3),
                                ])->columns(12)
                        ])
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
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\EditAction::make()
                    ->slideOver(),
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
            Store\CategoryResource\RelationManagers\AddressRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Store\OrderResource\Pages\ListOrders::route('/'),
            'create' => Store\OrderResource\Pages\CreateOrder::route('/create'),
            'view' => Store\OrderResource\Pages\ViewOrder::route('/{record}'),
            'edit' => Store\OrderResource\Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
