<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render("Admin/Products/Index", [
            "products" => Product::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render("Admin/Products/Create");
    }

    public function store(Request $request): RedirectResponse
    {
        Product::create([
            "ProductName" => $request->productName,
            "Description" => $request->description,
            "Category" => $request->category,
            "Price" => $request->price,
            "Stock" => $request->stock,
            "ImageUrl" => $request->imageUrl,
        ]);

        return redirect(route("products.index"));
    }

    public function show(string $id): Response
    {
        return Inertia::render("Admin/Products/Show", [
            "product" => Product::where("ProductID", $id)->first(),
        ]);
    }

    public function pos(): Response
    {
        return Inertia::render("Index", [
            "products" => Product::where("Stock", ">", 0)->get(),
        ]);
    }

    public function search(Request $request)
    {
        $product = Product::where("Stock", ">", 0);

        if ($request->query("q")) {
            $product = $product->where(
                "ProductName",
                "like",
                "%" . $request->query("q") . "%"
            );
        }

        if ($request->query("category")) {
            $product = $product->where("Category", $request->query("category"));
        }

        return response()->json([
            "success" => true,
            "products" => $product->get(),
        ]);
    }
}
