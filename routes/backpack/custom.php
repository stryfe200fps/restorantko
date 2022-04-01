<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('allergen', 'AllergenCrudController');
    Route::get('product/{id}/moderate', 'ProductCrudController@moderate');
    Route::post('product/{id}/upload', 'ProductCrudController@upload');
    Route::crud('address/{user_id?}', 'AddressCrudController');
    Route::crud('product-image', 'ProductImageCrudController');
    Route::crud('order', 'OrderCrudController');
    Route::crud('order-row', 'OrderRowCrudController');
    // Route::post('order', 'OrderCrudController@addToCart')->name('ajaxRequest.post');
    Route::post('order/status', 'OrderCrudController@status')->name('status');
    Route::get('/dashboard/monthly', function () {
        return Order::where('status', 'delivered')
        ->selectRaw(' monthname(ordered_at) as month, sum(total) as total_sale')
        ->groupBy('month')
        ->orderByRaw('min(ordered_at) desc')
        ->get();
    });
}); // this should be the absolute last line of this file
