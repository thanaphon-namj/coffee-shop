<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render("Admin/Customers/Index", [
            "customers" => Customer::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render("Admin/Customers/Create");
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create([
            "FirstName" => $request->firstName,
            "LastName" => $request->lastName,
            "BirthDate" => $request->birthDate,
            "PhoneNo" => $request->phoneNo,
            "RegisterDate" => Carbon::now(),
        ]);

        return redirect(route("customers.index"));
    }

    public function show(string $id): Response
    {
        return Inertia::render("Admin/Customers/Show", [
            "customer" => Customer::where("CustomerID", $id)->first(),
        ]);
    }

    public function search(Request $request)
    {
        return response()->json([
            "success" => true,
            "customer" => Customer::where(
                "PhoneNo",
                $request->query("q")
            )->first(),
        ]);
    }
}
