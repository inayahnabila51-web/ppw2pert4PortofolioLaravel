<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectGalleryController;
use App\Http\Controllers\SkillController;

// ===== PUBLIK =====
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/education', fn () => view('education'))->name('education');

Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects');
Route::get('/projects/{project:slug}', [PortfolioController::class, 'show'])
    ->name('projects.show');

// ===== WAJIB LOGIN + ADMIN =====
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin: /post/projects  |  nama route: admin.projects.*
    Route::prefix('post')->name('admin.')->group(function () {
        Route::get('/projects-trash', [ProjectController::class, 'trash'])->name('projects.trash');
        Route::put('/projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
        Route::delete('/projects/{id}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.forceDelete');
        Route::resource('projects', ProjectController::class);

        // Gallery
        Route::post('/projects/{project}/images', [ProjectGalleryController::class, 'store'])->name('projects.images.store');
        Route::put('/projects/{project}/images/{image}', [ProjectGalleryController::class, 'update'])->name('projects.images.update');
        Route::delete('/projects/{project}/images/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.images.destroy');
    });

    Route::resource('skills', SkillController::class)->only(['index', 'edit', 'update']);

    Route::get('/post/profile-saya', fn () => view('profile-saya'))->name('profile.saya');
});

require __DIR__.'/auth.php';