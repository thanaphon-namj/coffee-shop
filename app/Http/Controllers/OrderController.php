<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $customerID = $request->input('customerID');
        $details = $request->input('details');

        $order = Order::create([
            'Total' => $request->input('total'),
            'Payment' => $request->input('payment'),
            'PurchaseDate' => Carbon::now(),
            'EmployeeID' => $request->input('employeeID'),
            'CustomerID' => $customerID,
        ]);

        foreach ($details as $detail) {
            OrderDetail::create([
                'Quantity' => $detail['quantity'],
                'Total' => $detail['total'],
                'OrderID' => $order->OrderID,
                'ProductID' => $detail['productID'],
            ]);
        }

        if ($customerID) {
            Customer::where('CustomerID', $customerID)->increment('points', 100);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
    public function qr(Request $request): JsonResponse
    {
        $url = env('APP_URL', '');
        $uuid = sha1(time());
        Cache::put($uuid, false, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'qr' => $url . '/orders/pay/' . $uuid,
            'uuid' => $uuid,
        ]);
    }

    public function pay(string $id): Response
    {
        return Inertia::render('Orders/Pay', [
            'uuid' => $id,
        ]);
    }

    public function paid(Request $request): JsonResponse
    {
        $uuid = $request->input('uuid');
        Cache::put($uuid, true, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'paid' => true,
            'uuid' => $uuid,
        ]);
    }

    public function checkpay(Request $request): JsonResponse
    {
        $uuid = $request->input('uuid');
        $paid = Cache::get($uuid, false);

        return response()->json([
            'success' => true,
            'paid' => $paid,
            'uuid' => $uuid,
        ]);
    }
}
