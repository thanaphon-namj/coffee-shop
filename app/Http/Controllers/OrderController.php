<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render("Admin/Orders/Index", [
            "orders" => Order::all(),
        ]);
    }

    public function store(Request $request)
    {
        $employee = $request->session()->get("employee");

        $order = Order::create([
            "Total" => $request->total,
            "Payment" => $request->payment,
            "PurchaseDate" => Carbon::now(),
            "EmployeeID" => $employee["EmployeeID"],
            "CustomerID" => $request->customerID,
        ]);

        foreach ($request->details as $detail) {
            $order->details()->create([
                "Quantity" => $detail["quantity"],
                "Total" => $detail["total"],
                "OrderID" => $order->id,
                "ProductID" => $detail["productID"],
            ]);
        }

        return response()->json([
            "success" => true,
            "data" => $order,
        ]);
    }

    public function show(string $id): Response
    {
        return Inertia::render("Admin/Orders/Show", [
            "order" => Order::where("OrderID", $id)->first(),
        ]);
    }

    public function qr(Request $request)
    {
        $url = env("APP_URL", "");
        $uuid = sha1(time());
        $request->session()->put($uuid, false);

        return response()->json([
            "success" => true,
            "qr" => $url . "/orders/pay/" . $uuid,
            "uuid" => $uuid,
        ]);
    }

    public function pay(string $id): Response
    {
        return Inertia::render("Orders/Pay", [
            "uuid" => $id,
        ]);
    }

    public function paid(Request $request)
    {
        $request->session()->put($request->uuid, true);

        return response()->json([
            "success" => true,
            "uuid" => $request->uuid,
        ]);
    }

    public function checkPay(Request $request)
    {
        $paid = $request->session()->get($request->uuid, false);

        return response()->json([
            "success" => true,
            "paid" => $paid,
            "uuid" => $request->uuid,
        ]);
    }
}
