<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

// ===== PUBLIK =====
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/education', fn () => view('education'))->name('education');

// Breeze masih memanggil route 'dashboard'
Route::get('/dashboard', fn () => redirect()->route('projects.index'))->name('dashboard');

// ===== WAJIB LOGIN + ADMIN =====
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/projects-trash', [ProjectController::class, 'trash'])->name('projects.trash');
    Route::put('/projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('/projects/{id}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.forceDelete');
    Route::resource('projects', ProjectController::class);

    // Latihan 3
    Route::get('/post/profile-saya', fn () => view('profile-saya'))->name('profile.saya');
});

require __DIR__.'/auth.php';