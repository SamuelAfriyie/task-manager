<?php


use Illuminate\Support\Facades\Route;

// The Fallback Route (MUST BE LAST)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
