<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\Store;
use App\Filament\Resources\Store\CategoryResource\RelationManagers\AddressRelationManager;
use App\Models\Store\Order;
use App\Models\Store\Product;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;


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
                        ToggleButtons::make('payment_method')
                            ->inline()
                            ->default('pix')
                            ->required()
                            ->required()
                            ->options([
                                'pix' => 'PIX',
                                'cash' => 'Cash',
                                'boleto' => 'Boleto',
                                'creditcard' => 'Credit Card',
                                'debitcard' => 'Debit Card',
                                'stripe' => 'Stripe',
                                'paypal' => 'PayPal',
                            ])->colors([
                                'pix' => Color::Green,
                                'cash' => Color::Emerald,
                                'boleto' => Color::Blue,
                                'creditcard' => Color::Sky,
                                'debitcard' => Color::Orange,
                                'stripe' => Color::Fuchsia,
                                'paypal' => Color::Violet,
                            ]),
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
                                'new' => Color::Emerald,
                                'processing' => Color::Amber,
                                'shipped' => Color::Blue,
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
                        Select::make('shipping_method')
                                ->options([
                                    'instore' => 'In store sale',
                                    'pickup' => 'Pick Up',
                                    'bymail' => 'By Mail',
                                    'bycourier' => 'By courier',
                                    'airmail' => 'Airmail',
                                ])
                                ->default('instore'),

                        Textarea::make('shipping_amount')
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
                                        ->columnSpan(7)
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
                                        ->dehydrated()
                                        ->columnSpan(2),
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->dehydrated()
                                        ->columnSpan(2),
                                ])->columns(12),

                                    Placeholder::make('grand_total_placeholder')
                                        ->label('Grand Total')
                                        ->content(function (Get $get, Set $set) {
                                            $total = 0;
                                            if(!$repeaters = $get('items')) {
                                                return $total;
                                            }
                                            foreach ($repeaters as $key => $repeater) {
                                                $total += $get("items.{$key}.total_amount");
                                            }
                                            $set('grand_total', $total);
                                            return Number::currency($total, 'brl');
                                        }),
                                    Hidden::make('grand_total')
                                        ->default(0),
                        ])
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SelectColumn::make('payment_status')
                    ->label('Pay Status')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->label('Customer')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable()
                    ->money('BRL'),
                TextColumn::make('currency')
                    ->label("$")
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('payment_status')
                    ->label('Pay Status')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
                TextColumn::make('shipping_method')
                    ->label('Method')
                    ->searchable(),
                TextColumn::make('shipping_amount')
                    ->label('Delivery Cost')
                    ->numeric()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Delivery Status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])->searchable()->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    /**
     * @return string|null The navigation badge (count of records).
     */
    public static function getNavigationBadge(): ?string {
        /** @var Model|string $model */
        $model = static::getModel();
        if ($model) {
            return (string) $model::count();
        }
        return null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 0 ? 'success' : 'danger';
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
