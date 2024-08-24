<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Store\OrderResource;
use App\Models\Store\Order;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\View\View;

class LatestOrders extends BaseWidget
{
    protected  int | string | array $columnSpan = 'full';
//    protected static ?int $sort = 2; // This puts the stats on top of the table
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('user.name')
                ->searchable(),
                ImageColumn::make('image')
                    ->defaultImageUrl(url('https://tecdn.b-cdn.net/img/new/avatars/2.webp'))
                    ->circular(),
                TextColumn::make('grand_total')
                    ->money('BRL'),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()->sortable(true)
                    ->color(fn (string $state): array => match ($state) {
                        'new' => Color::Emerald,
                        'processing' => Color::Amber,
                        'shipped' => Color::Blue,
                        'delivered' => Color::Green,
                        'cancelled' => Color::Red,
                    })->icon(fn(string $state): string => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                    }),
                TextColumn::make('payment_status')
                    ->label('Pay Status')
                    ->searchable()->sortable(true)
                    ->badge()
                    ->color(fn (string $state): array => match ($state) {
                        'pending' => Color::Amber,
                        'paid' => Color::Green,
                        'failed' => Color::Red,
                    })->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-arrow-path',
                        'paid' => 'heroicon-m-check-badge',
                        'failed' => 'heroicon-m-x-circle',
                    }),
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge()
                    ->searchable()->sortable(true)
                    ->color(fn(string $state): array => match ($state) {
                        'pix' => Color::Green,
                        'cash' => Color::Emerald,
                        'boleto' => Color::Blue,
                        'creditcard' => Color::Sky,
                        'debitcard' => Color::Orange,
                        'stripe' => Color::Fuchsia,
                        'paypal' => Color::Violet,
                    })->icon(''),
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('If Updated')
                    ->dateTime(),
            ])->actions([
                Action::make('View Order')
                ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                ->icon('heroicon-s-eye'),
            ]);
    }
}
