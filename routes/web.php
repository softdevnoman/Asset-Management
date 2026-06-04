<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('login'); // or wherever you want the home page to go
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'LoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'super_admin') {
            return redirect()->route('accounts');
        }
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:super_admin, admin, employee'])->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});

Route::middleware(['auth', 'role:super_admin, admin, employee '])->group(function () {
    Route::get('/manage-assets', [AssetController::class, 'index'])->name('assets');
    Route::post('/manage-assets', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/manage-assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
    Route::put('/manage-assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('/manage-assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{user}', [AccountController::class, 'show'])->name('accounts.show');
    Route::put('/accounts/{user}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{user}', [AccountController::class, 'destroy'])->name('accounts.destroy');
});
