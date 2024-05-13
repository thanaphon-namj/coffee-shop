<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function pos(): Response
    {
        return Inertia::render('Index', [
            'products' => Product::where('Stock', '>', 0)->get(),
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->query('q');
        $category = $request->query('category');
        $product = Product::where('Stock', '>', 0);

        if ($q) {
            $product = $product->where('ProductName', 'like', '%' . $q . '%');
        }

        if ($category) {
            $product = $product->where('Category', $category);
        }

        return response()->json([
            'success' => true,
            'products' => $product->get(),
        ]);
    }
}
