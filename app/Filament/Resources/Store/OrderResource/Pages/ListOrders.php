<?php

namespace App\Filament\Resources\Store\OrderResource\Pages;

use App\Filament\Resources\Store\OrderResource;
use App\Filament\Resources\Store\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

//    protected function getFooterWidgets(): array
//    {
//        return [
//            OrderStats::class,
//        ];
//    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('ALL')
                ->query(fn($query) => $query),  // Query for all orders

            'new' => Tab::make('New')
                ->query(fn($query) => $query->where('status', 'new')),  // Tab for new orders

            'processing' => Tab::make('Processing')
                ->query(fn($query) => $query->where('status', 'processing')),  // Tab for processing orders

            'shipped' => Tab::make('Shipped')
                ->query(fn($query) => $query->where('status', 'shipped')),  // Tab for shipped orders

            'delivered' => Tab::make('Delivered')
                ->query(fn($query) => $query->where('status', 'delivered')),  // Tab for delivered orders

            'canceled' => Tab::make('Canceled')
                ->query(fn($query) => $query->where('status', 'canceled')),  // Tab for canceled orders

            'pending' => Tab::make('Pending')
                ->query(fn($query) => $query->where('status', 'pending')),  // Tab for pending orders

            'paid' => Tab::make('Paid')
                ->query(fn($query) => $query->where('status', 'paid')),  // Tab for paid orders

            'failed' => Tab::make('Failed')
                ->query(fn($query) => $query->where('status', 'failed')),  // Tab for failed orders
        ];
    }
}
