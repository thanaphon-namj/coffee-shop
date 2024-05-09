<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Orders/Index', [
            'orders' => Order::with(['employee', 'customer'])->get(),
        ]);
    }

    public function show(string $id): Response
    {
        return Inertia::render('Admin/Orders/Show', [
            'order' => Order::where('OrderID', $id)->first(),
        ]);
    }
}
