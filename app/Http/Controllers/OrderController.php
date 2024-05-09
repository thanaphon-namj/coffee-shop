<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function qr(Request $request): JsonResponse
    {
        $url = env('APP_URL', '');
        $uuid = sha1(time());
        $request->session()->put($uuid, false);

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
        $request->session()->put($uuid, true);

        return response()->json([
            'success' => true,
            'uuid' => $uuid,
        ]);
    }

    public function checkpay(Request $request): JsonResponse
    {
        $uuid = $request->input('uuid');
        $paid = $request->session()->get($uuid, false);

        return response()->json([
            'success' => true,
            'paid' => $paid,
            'uuid' => $uuid,
        ]);
    }
}
