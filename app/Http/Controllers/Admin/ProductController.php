<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Products/Index', [
            'products' => Product::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        Product::create([
            'ProductName' => $request->input('productName'),
            'Description' => $request->input('description'),
            'Category' => $request->input('category'),
            'Price' => $request->input('price'),
            'Stock' => $request->input('stock'),
            'ImageUrl' => $request->input('imageUrl'),
        ]);

        return redirect(route('products.index'));
    }

    public function show(string $id): Response
    {
        return Inertia::render('Admin/Products/Show', [
            'product' => Product::where('ProductID', $id)->first(),
            'canDelete' => OrderDetail::where('ProductID', $id)->first() === null,
        ]);
    }

    public function destroy(string $id): RedirectResponse
    {
        Product::where('ProductID', $id)->delete();

        return redirect(route('products.index'));
    }
}
