<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get("/", [ProductController::class, "pos"])->name("pos");

Route::prefix("admin")->group(function () {
    Route::redirect("/", "/admin/dashboard");
    // Dashboard
    Route::get("/dashboard", function () {
        return Inertia::render("Admin/Dashboard");
    })->name("dashboard");
    // Orders
    Route::get("/orders", [OrderController::class, "index"])->name(
        "orders.index"
    );
    Route::get("/orders/{id}", [OrderController::class, "show"])->name(
        "orders.show"
    );
    // Products
    Route::get("/products", [ProductController::class, "index"])->name(
        "products.index"
    );
    Route::post("/products", [ProductController::class, "store"])->name(
        "products.store"
    );
    Route::get("/products/create", [ProductController::class, "create"])->name(
        "products.create"
    );
    Route::get("/products/{id}", [ProductController::class, "show"])->name(
        "products.show"
    );
    // Customers
    Route::get("/customers", [CustomerController::class, "index"])->name(
        "customers.index"
    );
    Route::post("/customers", [CustomerController::class, "store"])->name(
        "customers.store"
    );
    Route::get("/customers/create", [
        CustomerController::class,
        "create",
    ])->name("customers.create");
    Route::get("/customers/{id}", [CustomerController::class, "show"])->name(
        "customers.show"
    );
    // Employees
    Route::get("/employees", [EmployeeController::class, "index"])->name(
        "employees.index"
    );
    Route::post("/employees", [EmployeeController::class, "store"])->name(
        "employees.store"
    );
    Route::get("/employees/create", [
        EmployeeController::class,
        "create",
    ])->name("employees.create");
    Route::get("/employees/{id}", [EmployeeController::class, "show"])->name(
        "employees.show"
    );
});

Route::get("/products", [ProductController::class, "search"])->name(
    "products.search"
);

Route::get("/customers", [CustomerController::class, "search"])->name(
    "customers.search"
);

Route::controller(OrderController::class)->group(function () {
    Route::post("/orders", "store")->name("orders.store");
    Route::post("/orders/qr", "qr")->name("orders.qr");
    Route::get("/orders/pay/{id}", "pay");
    Route::post("/orders/paid", "paid")->name("orders.paid");
    Route::post("/orders/pay/check", "checkPay")->name("orders.pay.check");
});

Route::post("/upload", [UploadController::class, "store"])->name(
    "upload.store"
);

Route::get("/login", [EmployeeController::class, "logins"])->name("logins");
Route::post("/login", [EmployeeController::class, "login"])->name("login");
Route::post("/logout", [EmployeeController::class, "destroy"])->name("logout");
