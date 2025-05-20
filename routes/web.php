<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs.postman', function () {
    return response()->file(storage_path('app/private/scribe/collection.json'), [
        'Content-Type' => 'application/json',
    ]);
});