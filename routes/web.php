<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AssetController;

Route::get('/', function () {
    return redirect('login'); // or wherever you want the home page to go
});

Route::middleware('guest')->group(function(){
    Route::get('/register', [AuthController::class , 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'LoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);


});

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/employees', function() {
        return 'Employee management page coming soon!';
    })->name('employees');
});

Route::middleware('auth')->group(function(){
    Route::get('/manage-assets', [AssetController::class, 'index'])->name('assets');
    Route::post('/manage-assets', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/manage-assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
    Route::put('/manage-assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('/manage-assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
});
