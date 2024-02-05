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

Route::get('/', [EventController::class, 'index']); //mostra todos os registros. Listagem
Route::get('/events/create', [EventController::class, 'create'])->middleware('auth'); //Criar com registro no banco. middleware('auth') ->usuário só tem acesso se estiver logado
Route::get('/events/{id}', [EventController::class, 'show']); //mostra um dado específico
Route::post('/events', [EventController::class, 'store']); //enviar os dados no banco
Route::get('/dashboard', [EventController::class, 'dashboard'])->middleware('auth');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->middleware('auth');
Route::get('/events/edit/{id}', [EventController::class, 'edit'])->middleware('auth');
Route::put('/events/update/{id}', [EventController::class, 'update'])->middleware('auth');
Route::post('/events/join/{id}', [EventController::class, 'joinEvent'])->middleware('auth');
Route::delete('/events/leave/{id}', [EventController::class, 'leaveEvent'])->middleware('auth');

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