<?php

use App\Http\Controllers\Api\Offers\OffersController;
use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Offers'], function () {
    Route::get('/offers', [OffersController::class, 'list'])->name('api.offers.list');
});
