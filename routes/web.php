<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MqttController;
use App\Http\Controllers\DeviceController;

/* ===============================
   ROUTE UMUM
=============================== */

// Route::get('/PDla', [TittleController::class,'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/padela', function () {
    return view('padela');
});

Route::get('/fadla/{kelas}/{asal}', function($kelas, $asal){
    return "fadla " . $kelas . " " . $asal;
});

Route::get('/fadla', function() {
    $page = request("page");
    $short = request("short");
    return 'Halaman ' . $page . ' ' . $short;
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



Route::get('/sensor', [SensorController::class, 'index'])
    ->middleware('auth')
    ->name('sensor.index');

Route::get('/sensor/create', [SensorController::class, 'create'])
    ->name('sensor.create');

Route::post('/sensor', [SensorController::class, 'store'])
    ->name('sensor.store');

Route::get('/sensor/edit/{id}', [SensorController::class, 'edit'])
    ->name('sensor.edit');

Route::put('/sensor/{id}', [SensorController::class, 'update'])
    ->name('sensor.update');

Route::delete('/sensor/{id}', [SensorController::class, 'delete'])
    ->name('sensor.delete');



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

Route::get('/device', [DeviceController::class, 'index']);
Route::get('/device/create', [DeviceController::class, 'create']);
Route::post('/device', [DeviceController::class, 'store'])->name('device.store');
Route::put('/device/{id}', [DeviceController::class, 'update'])->name('device.update');
Route::delete('/device/{id}', [DeviceController::class, 'destroy'])->name('device.delete');


Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/register', [AuthController::class, 'register']);

Route::post('/register', [AuthController::class, 'storeRegister']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


Route::middleware('auth')->group(function () {
    Route::post('/mqtt/publish', [MqttController::class, 'publish'])->name('mqtt.publish');
    Route::get('/api/sensors/latest', [MqttController::class, 'latestData'])->name('sensors.latest');
    Route::get('/api/devices/status', [DeviceController::class, 'statusApi'])->name('devices.status');
});