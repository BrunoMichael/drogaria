<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\VendedorController;
use App\Http\Controllers\Api\OrcamentoController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('produtos', ProdutoController::class);
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('vendedors', VendedorController::class);
Route::apiResource('orcamentos', OrcamentoController::class);