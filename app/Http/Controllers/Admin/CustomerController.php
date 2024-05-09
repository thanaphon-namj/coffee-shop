<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return Inertia::render('Admin/Customers/Index', [
            'customers' => Customer::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Customers/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create([
            'FirstName' => $request->input('firstName'),
            'LastName' => $request->input('lastName'),
            'BirthDate' => $request->input('birthDate'),
            'PhoneNo' => $request->input('phoneNo'),
            'RegisterDate' => Carbon::now(),
        ]);

        return redirect(route('customers.index'));
    }

    public function show(string $id): Response
    {
        return Inertia::render('Admin/Customers/Show', [
            'customer' => Customer::where('CustomerID', $id)->first(),
        ]);
    }
}
