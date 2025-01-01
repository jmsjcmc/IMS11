<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['role:Admin']], function () {
    Route::get('/admin-dashboard', [AdminController::class, 'viewAdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/add-user', [AdminController::class, 'addUser'])->name('admin.adduser');
    Route::post('/users', [AdminController::class, 'create'])->name('admin.create');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/users/{user}', [AdminController::class, 'delete'])->name('admin.delete');
});

Route::group(['middleware' => ['role:User']], function () {
    Route::get('/user-dashboard', [UserController::class, 'viewUserDashboard'])->name('user.dashboard');
});

require __DIR__.'/auth.php';
