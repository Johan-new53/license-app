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
use App\Http\Controllers\AutomateController;
use App\Http\Controllers\CheckNoController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ReksumberController;
use App\Http\Controllers\MatauangController;
use App\Http\Controllers\RektujuanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PpnController;
use App\Http\Controllers\Approval1Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DigitalController;
use App\Http\Controllers\PayableController;

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
    Route::resource('automates', AutomateController::class);
    Route::resource('approvals', Approval1Controller::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('digitals', DigitalController::class);

    Route::get('/import', [ImportController::class, 'index'])->name('import');
    Route::post('/import', [ImportController::class, 'upload'])->name('import.upload');



    Route::get('/payments/export/{payment_date?}', [PaymentController::class,'export'])->name('payments.export');
    Route::get('/payments/export_paid/{payment_date?}', [PaymentController::class,'export_paid'])->name('payments.export_paid');

    //MASTER DATA
    Route::resource('bank', BankController::class);
    Route::resource('department', DepartmentController::class);
    Route::resource('reksumber', ReksumberController::class);
    Route::resource('matauang', MatauangController::class);
    Route::resource('rektujuan', RektujuanController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('ppn', PpnController::class);

    Route::resource('payable', PayableController::class)->except(['show']);
    Route::post('payable/sync', [PayableController::class, 'sync'])->name('payable.sync');
    Route::post('/payable/import', [PayableController::class, 'import'])->name('payable.import');
    Route::get('/payable/export', [PayableController::class, 'export'])->name('payable.export');

    Route::get('/users/{id}/change', [UserController::class, 'change'])->name('users.change');
    Route::put('/users/{id}/update_pwd', [UserController::class, 'update_pwd'])->name('users.update_pwd');

    Route::post('/check-doc-no', [CheckNoController::class, 'checkDocNo'])->name('checkDocNo');

    Route::get('/export-products', [ProductController::class, 'export']);
    Route::get('/searchitem',[ProductController::class, 'searchitem']);
    Route::get('/searchname',[UserController::class, 'searchname']);
    Route::post('/import-csv', [CsvImportController::class, 'import']);

    Route::get('/email-status', [EmailStatusController::class, 'send']);

    Route::get('/get-ppn/{id}', function ($id) {
    return \App\Models\Ppn::findOrFail($id);
    })->name('get.ppn');




});
