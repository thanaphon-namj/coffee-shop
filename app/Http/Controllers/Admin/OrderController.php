<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\RedirectResponse;
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
            'order' => Order::with(['employee', 'customer', 'details', 'details.product'])->where('OrderID', $id)->first(),
            'canDelete' => OrderDetail::where('OrderID', $id)->first() === null,
        ]);
    }

    public function destroy(string $id): RedirectResponse
    {
        OrderDetail::where('OrderID', $id)->delete();
        Order::where('OrderID', $id)->delete();
        return redirect(route('orders.index'));
    }
}
