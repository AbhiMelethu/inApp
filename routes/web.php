<?php

use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    return Redirect::route('import.form');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Import
    Route::get('/import', [ImportController::class, 'view'])->name('import.form');
    Route::post('/import/save', [ImportController::class, 'import'])->name('import.save');
});

require __DIR__.'/auth.php';
