<?php

namespace App\Livewire;

use App\Models\Store\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('catgeories - Noddie')]
class CategoriesPage extends Component
{
    public $categories;

    public function mount()
    {
        $this->categories = Category::where('is_active', 1)->get(); // Adjust the query as needed
    }

    public function render()
    {
        return view('livewire.categories-page', [
            'categories' => $this->categories
        ]);
    }
}
