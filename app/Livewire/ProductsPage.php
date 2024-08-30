<?php

namespace App\Livewire;

use App\Models\Store\Brand;
use App\Models\Store\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - Noddie')]
class ProductsPage extends Component
{
    use withPagination;
    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);
        return view('livewire.products-page', [
        'products' => $productQuery->paginate(6),
        'brands' => Brand::where('is_active', 1)->get('i),
        ]);
    }
}
