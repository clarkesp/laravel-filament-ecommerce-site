<?php

namespace App\Filament\Resources\Store\OrderResource\Widgets;

use App\Models\Store\Order;
use Filament\Infolists\Components\Section;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OrderStats extends BaseWidget
{
//    protected static string $heading = 'Order Statistics';
//    protected int | STRING | array $columnSpan = 'full';
//    protected static ?string $maxHeight ='50px';
    protected function getStats(): array
    {
        return [
            // ->formatStateUsing(fn ($state) => '$' . number_format($state, 2)) // create this function
            // Average Income
            Stat::make('Average Income', Order::query()->avg('grand_total'))
                ->label('Average Income')
                ->description('Average order value')
                ->color('success')
                ->icon('heroicon-o-currency-dollar')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            // Total Income for the Year
            Stat::make('Total Income', Order::query()
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('grand_total'))
                ->label('Total Income (YTD)')
                ->description('Total income for the current year')
                ->color('primary')
                ->icon('heroicon-o-chart-bar')
                ->descriptionIcon('heroicon-m-calendar', IconPosition::Before)
                ->chart([10, 20, 15, 25, 30, 35, 40]),

            // New Orders
            Stat::make('New Orders', Order::query()->where('status', 'new')->count())
                ->label('New Orders')
                ->description('New orders placed')
                ->color('success')
                ->icon('heroicon-o-shopping-cart')
                ->chart([3, 7, 8, 12, 14, 9, 5]),

            // Orders Processing
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count())
                ->label('Orders Processing')
                ->description('Orders currently being processed')
                ->color('warning')
                ->icon('heroicon-o-cog')
                ->chart([8, 12, 5, 9, 13, 7, 3]),

            // Shipped Orders
            Stat::make('Shipped Orders', Order::query()->where('status', 'shipped')->count())
                ->label('Shipped Orders')
                ->description('Orders shipped to customers')
                ->color('primary')
                ->icon('heroicon-o-truck')
                ->chart([5, 10, 3, 12, 8, 15, 7]),

            // Delivered Orders
            Stat::make('Delivered Orders', Order::query()->where('status', 'delivered')->count())
                ->label('Delivered Orders')
                ->description('Orders successfully delivered')
                ->color('info')
                ->icon('heroicon-o-check-circle')
                ->chart([10, 14, 8, 20, 15, 9, 6]),

            // Canceled Orders
            Stat::make('Canceled Orders', Order::query()->where('status', 'canceled')->count())
                ->label('Canceled Orders')
                ->description('Orders canceled by customers')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->chart([1, 2, 1, 3, 2, 4, 1]),

            // Pending Orders
            Stat::make('Pending Orders', Order::query()->where('status', 'pending')->count())
                ->label('Pending Orders')
                ->description('Orders awaiting confirmation')
                ->color('gray')
                ->icon('heroicon-o-clock')
                ->chart([4, 5, 2, 7, 3, 8, 4]),

            // Paid Orders
            Stat::make('Paid Orders', Order::query()->where('status', 'paid')->count())
                ->label('Paid Orders')
                ->description('Orders paid by customers')
                ->color('success')
                ->icon('heroicon-o-credit-card')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->chart([14, 18, 10, 22, 25, 19, 13]),

            // Failed Orders
            Stat::make('Failed Orders', Order::query()->where('status', 'failed')->count())
                ->label('Failed Orders')
                ->description('Orders that failed payment or processing')
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "\$dispatch('setStatusFilter', { filter: 'processed' })",
                ])
                ->chart([7, 2, 10, 3, 15, 4, 17]),


        ];
    }
}
