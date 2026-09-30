<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;  
Route::resource('projects', ProjectController::class);

Route::get('/projects-trash', [ProjectController::class, 'trash'])->name('projects.trash');
Route::put('/projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
Route::delete('/projects/{id}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.forceDelete');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/education', function () {
    return view('education');
})->name('education');

