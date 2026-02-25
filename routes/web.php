<?php

use App\Http\Controllers\TittleController;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Controller;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\TodoController;

Route::get('/PDla', [TittleController::class,'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/padela', function () {
    return view('padela');
});

Route::get('/fadla/{kelas}/{asal}', function($kelas, $asal){
    return "fadla" . $kelas .$asal;
});

Route::get('/fadla', function() {
    $page = request("page");
    $short = request("short");
    return 'Halaman' . $page . $short;
});

Route::post('/padla', function() {
    return 'success';
});

Route::post('/Pdla', function() {
    $article = [
        "title" => request("judul"),
        "content" => request("isi")   
    ];
        return $article; 
    });

 Route::get('/sensor', [SensorController::class, 'index']);
Route::get('/tambah', function () {
    return view('tambah');
});
Route::post('/sensor/store', [SensorController::class, 'store']);



Route::get('/sensor', [SensorController::class, 'index']);
Route::get('/sensor/create', [SensorController::class, 'create']);
Route::post('/sensor', [SensorController::class, 'store']);
Route::put('/sensor/edit/{id}', [SensorController::class, 'edit']);
Route::put('/sensor/{id}', [SensorController::class, 'update']);
Route::delete('/sensor/{id}', [SensorController::class, 'delete']);





Route::get('/todo', [TodoController::class, 'index'])
    ->name('todo.index');
Route::post('/todo', [TodoController::class, 'store'])
    ->name('todo.store');
Route::put('/todo/{id}', [TodoController::class, 'update'])
    ->name('todo.update');
Route::delete('/todo/{id}', [TodoController::class, 'destroy'])
    ->name('todo.destroy');
Route::put('/todo/{id}/update-text', [TodoController::class, 'updateText'])
    ->name('todo.updateText');