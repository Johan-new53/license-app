<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\EmailStatusController;
use App\Http\Controllers\HardcopyController;
use App\Http\Controllers\SoftcopyController;


Route::get('/login/microsoft', [LoginController::class, 'redirectToMicrosoft'])->name('login.microsoft');
Route::get('/login/microsoft/callback', [LoginController::class, 'handleMicrosoftCallback']);

// Redirect root URL to /home if logged in, or to login otherwise
Route::get('/', function () {
    

    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login'); // or return view('welcome');
});

// Auth routes (login, register, forgot password, etc.)
Auth::routes();

// Home page after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Protected routes (only accessible when logged in)
Route::middleware(['auth'])->group(function () {
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('hardcopys', HardcopyController::class);
    Route::resource('softcopys', SoftcopyController::class);
    
    
    Route::get('/users/{id}/change', [UserController::class, 'change'])->name('users.change');
    Route::put('/users/{id}/update_pwd', [UserController::class, 'update_pwd'])->name('users.update_pwd');

    Route::get('/export-products', [ProductController::class, 'export']);
    Route::get('/searchitem',[ProductController::class, 'searchitem']);
    Route::get('/searchname',[UserController::class, 'searchname']);
    Route::post('/import-csv', [CsvImportController::class, 'import']);   

    Route::get('/email-status', [EmailStatusController::class, 'send']);

    
    


});