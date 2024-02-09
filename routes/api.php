<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API's Autores
Route::get('listAutor', [AutorController::class, 'index']);
Route::get('findByLikeAutor', [AutorController::class, 'findByLike']);
Route::get('allAutor', [AutorController::class, 'combo']);
Route::post('registerAutor', [AutorController::class, 'register']);
Route::get('editAutor/{id?}', [AutorController::class, 'edit']);
Route::put('updateAutor/{id}', [AutorController::class, 'update']);
Route::delete('destroyAutor/{id}', [AutorController::class, 'destroy']);

// API's Clientes
Route::get('listCliente', [ClienteController::class, 'index']);
Route::get('findByLike', [ClienteController::class, 'findByLike']);
Route::get('allCliente', [ClienteController::class, 'combo']);
Route::post('registerCliente', [ClienteController::class, 'register']);
Route::get('editCliente/{id?}', [ClienteController::class, 'edit']);
Route::put('updateCliente/{id}', [ClienteController::class, 'update']);
Route::delete('destroyCliente/{id}', [ClienteController::class, 'destroy']);

// API's Libros
Route::get('listLibro', [LibroController::class, 'index']);
Route::get('findByLikeLibro', [LibroController::class, 'findByLike']);
Route::get('allLibro', [LibroController::class, 'combo']);
Route::post('registerLibro', [LibroController::class, 'register']);
Route::get('editLibro/{id?}', [LibroController::class, 'edit']);
Route::put('updateLibro/{id}', [LibroController::class, 'update']);
Route::delete('destroyLibro/{id}', [LibroController::class, 'destroy']);

// API's Prestamos
Route::get('listPrestamos', [PrestamosController::class, 'index']);
Route::get('findByLikePrestamos', [PrestamosController::class, 'findByLike']);
Route::post('registerPrestamos', [PrestamosController::class, 'register']);
Route::get('editPrestamos/{id?}', [PrestamosController::class, 'edit']);
Route::put('updatePrestamos/{id}', [PrestamosController::class, 'update']);
Route::delete('destroyPrestamos/{id}', [PrestamosController::class, 'destroy']);

// API's Reportes
Route::get('prestamosVencidos', [PrestamosController::class, 'prestamosVencidos']);
Route::get('likeprestamosVencidos', [PrestamosController::class, 'findByLikeprestamosVencidos']);
Route::get('prestamosXmes', [PrestamosController::class, 'segmentadosXmes']);
Route::get('prestamosXmesXsemana', [PrestamosController::class, 'segmentadosXmesXsemana']);