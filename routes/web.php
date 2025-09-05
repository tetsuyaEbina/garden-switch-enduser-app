<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\UserCompanyController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\User\AuthController AS UserAuthController;
use App\Http\Controllers\User\DashboardController AS UserDashboardController;

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

        //user_companies関連
        Route::get('user_companies/index', [UserCompanyController::class, 'index'])->name('user_companies.index');
        Route::get('user_companies/create', [UserCompanyController::class, 'create'])->name('user_companies.create');
        Route::post('user_companies/store', [UserCompanyController::class, 'store'])->name('user_companies.store');
        Route::get('user_companies/edit/{id}', [UserCompanyController::class, 'edit'])->name('user_companies.edit');
        Route::put('user_companies/update/{id}', [UserCompanyController::class, 'update'])->name('user_companies.update');
        Route::post('user_companies/delete/{id}', [UserCompanyController::class, 'delete'])->name('user_companies.delete');
        Route::put('user_companies/restore/{id}', [UserCompanyController::class, 'restore'])->name('user_companies.restore');

        //users関連
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'delete'])->name('users.delete');
        Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::post('/users/{id}/reset_password', [UserController::class, 'resetPassword'])->name('users.reset_password');
    });
});

Route::prefix('user')->name('user.')->group(function () {
    // ログイン,ログアウト処理
    Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('logout', [UserAuthController::class, 'logout'])->name('logout');

    // ログイン後の管理画面(auth:user ミドルウェア保護)
    Route::middleware(['auth:user'])->group(function () {
        Route::get('home', [UserDashboardController::class, 'index'])->name('home');

        // パスワード再設定
        Route::get('reset_password', [UserAuthController::class, 'showResetPasswordForm'])->name('reset_password.form');
        Route::post('reset_password', [UserAuthController::class, 'resetPassword'])->name('reset_password');
    });
});
