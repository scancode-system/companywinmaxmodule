<?php

Route::prefix('exports')->group(function() {
    Route::get('txt/orders/winmax', 'ExportController@txtOrders')->name('exports.txt.orders.winmax');
});