<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UploadController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::middleware(Authenticate::class)->group(function () {
    Route::get('/', [ProductController::class, 'pos'])->name('pos');

    Route::prefix('admin')->group(function () {
        Route::redirect('/', '/admin/dashboard');
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        // Orders
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
        // Products
        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class,'store'])->name('products.store');
        Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
        Route::get('/products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
        Route::delete('/products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
        // Customers
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::post('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/create', [\App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customers.create');
        Route::get('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
        Route::delete('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        // Employees
        Route::get('/employees', [\App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/employees', [\App\Http\Controllers\Admin\EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/create', [\App\Http\Controllers\Admin\EmployeeController::class, 'create'])->name('employees.create');
        Route::get('/employees/{id}', [\App\Http\Controllers\Admin\EmployeeController::class, 'show'])->name('employees.show');
        Route::delete('/employees/{id}', [\App\Http\Controllers\Admin\EmployeeController::class, 'destroy'])->name('employees.destroy');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [EmployeeController::class, 'index'])->name('login');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('/orders', 'store')->name('orders.store');
    Route::post('/orders/qr', 'qr')->name('orders.qr');
    Route::get('/orders/pay/{id}', 'pay');
    Route::post('/orders/paid', 'paid')->name('orders.paid');
    Route::post('/orders/pay/check', 'checkpay')->name('orders.checkpay');
});

Route::get('/products', [ProductController::class, 'search'])->name('products.search');

Route::get('/customers', [CustomerController::class, 'search'])->name('customers.search');

Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

Route::post('/login', [EmployeeController::class, 'login']);
Route::post('/logout', [EmployeeController::class, 'destroy'])->name('logout');
