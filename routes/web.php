<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // ログイン,ログアウト処理
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // ログイン後の管理画面(auth:admin ミドルウェア保護)
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('home', [DashboardController::class, 'index'])->name('home');

        //admins関連
        Route::get('admins/index',  [AdminUserController::class, 'index'])->name('admins.index');
        Route::get('admins/create', [AdminUserController::class, 'create'])->name('admins.create');
        Route::post('admins/store', [AdminUserController::class, 'store'])->name('admins.store');
        Route::get('admins/edit/{id}', [AdminUserController::class, 'edit'])->name('admins.edit');
        Route::post('admins/update/{id}', [AdminUserController::class, 'update'])->name('admins.update');
        Route::post('admins/delete/{id}', [AdminUserController::class, 'delete'])->name('admins.delete');
        Route::post('admins/reset_password/{id}', [AdminUserController::class, 'resetPassword'])->name('admins.reset_password');
        Route::get('/profile/password', [AdminUserController::class, 'editPassword'])->name('admins.password.edit');
        Route::post('/profile/password', [AdminUserController::class, 'updatePassword'])->name('admins.password.update');
    });
});
