<?php

use App\Livewire\CategoriesPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
