<?php

namespace App\Livewire;

use App\Models\Store\Brand;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Home Page - Ecommerce')]
class HomePage extends Component
{
    public function render()
    {
        $brands = Brand::where('is_active', 1)->get();
        dd($brands);
        return view('livewire.home-page');
    }
}
