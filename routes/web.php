<?php


use Illuminate\Support\Facades\Route;

// The Fallback Route
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
