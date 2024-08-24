<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\Store\OrderResource;
use App\Models\Store\Order;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define form fields here if needed
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                ImageColumn::make('image')
                    ->defaultImageUrl(url('https://tecdn.b-cdn.net/img/new/avatars/2.webp'))
                    ->circular(),
                TextColumn::make('grand_total')
                    ->money('BRL'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(true)
                    ->color(fn(string $state): array => match($state) {
                        'new' => Color::Green,
                        'processing' => Color::Amber,
                        'shipped' => Color::Fuchsia,
                        'delivered' => Color::Emerald,
                        'cancelled' => Color::Red,
                        default => Color::Gray, // Ensure there is a default case
                    }),
                TextColumn::make('payment_status')
                    ->label('Pay Status')
                    ->sortable(true)
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): array => match($state) {
                        'pending' => Color::Amber,
                        'paid' => Color::Green,
                        'failed' => Color::Red,
                        default => Color::Gray, // Ensure there is a default case
                    }),
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable(true)
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): array => match($state) {
                        'pix' => Color::Green,
                        'cash' => Color::Emerald,
                        'boleto' => Color::Blue,
                        'creditcard' => Color::Indigo,
                        'debitcard' => Color::Cyan,
                        'stripe' => Color::Fuchsia,
                        'paypal' => Color::Teal,
                        default => Color::Gray, // Ensure there is a default case
                    }),
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('If Updated')
                    ->dateTime(),
            ])
            ->filters([
                // Define any filters here if needed
            ])
            ->headerActions([
                // Uncomment if you want to add a create action
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('View Order')
            ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
            ->color('info')
            ->icon('heroicon-s-eye'),
                    DeleteAction::make(),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
