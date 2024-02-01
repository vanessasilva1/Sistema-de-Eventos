<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [EventController::class, 'index']);
Route::get('/events/create', [EventController::class, 'create']);
Route::post('/events', [EventController::class, 'store']);


//Route::get('/contact', function () {
//    return view('contact');
//});

//Route::get('/produtos', function () {

//    $busca = request('search');
//    return view('products', ['busca' => $busca]);
    
//});

//Route::get('/produtos_testes/{id?}', function ($id = null) {
//    return view('product', ['id' => $id]);
//});