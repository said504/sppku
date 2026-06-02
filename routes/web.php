<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\ApiController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Parent Routes
Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboard::class, 'index'])->name('dashboard');
    Route::get('/invoices', [ParentDashboard::class, 'invoices'])->name('invoices');
    Route::get('/history', [ParentDashboard::class, 'history'])->name('history');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Students CRUD
    Route::get('/students', [AdminDashboard::class, 'students'])->name('students');
    Route::post('/students', [AdminDashboard::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{id}', [AdminDashboard::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [AdminDashboard::class, 'deleteStudent'])->name('students.delete');
    
    // Rules CRUD
    Route::get('/rules', [AdminDashboard::class, 'rules'])->name('rules');
    Route::post('/rules', [AdminDashboard::class, 'storeRule'])->name('rules.store');
    Route::delete('/rules/{id}', [AdminDashboard::class, 'deleteRule'])->name('rules.delete');

    Route::get('/tunggakan', [AdminDashboard::class, 'tunggakan'])->name('tunggakan');
});

// API Routes for AJAX (Protected by auth)
Route::middleware('auth')->group(function () {
    Route::post('/api/pay', [ApiController::class, 'payInvoice']);
    Route::get('/api/admin-data', [ApiController::class, 'getAdminData']);
});
