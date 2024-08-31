<?php

namespace App\Livewire;

use App\Models\Store\Brand;
use App\Models\Store\Category;
use App\Models\Store\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - Noddie')]
class ProductsPage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_categories = [];
    #[Url]
    public $selected_brands = [];
    #[Url]
    public $featured = false;
    #[Url]
    public $onSale = false;
    #[Url]
    public $price_range = 100000;

    public function render()
    {
        // Initialize the product query
        $productQuery = Product::query()->where('is_active', 1);

        // Apply category filter if any categories are selected
        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        // Apply brand filter if any brands are selected
        if (!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        // Apply featured filter only if 'featured' is true
        if ($this->featured) {
            $productQuery->where('is_featured', true);
        }

        // Apply on sale filter only if 'onSale' is true
        if ($this->onSale) {
            $productQuery->where('on_sale', true);
        }

        if ($this->price_range) {
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(9),
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
