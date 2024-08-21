<?php

namespace App\Filament\Resources\Store\OrderResource\Pages;

use App\Filament\Resources\Store\OrderResource;
use App\Filament\Resources\Store\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
            OrderStats::class
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            OrderStats::class
            ];
    }
}
