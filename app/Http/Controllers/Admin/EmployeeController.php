<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Employees/Index', [
            'employees' => Employee::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Employees/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        Employee::create([
            'FirstName' => $request->input('firstName'),
            'LastName' => $request->input('lastName'),
            'BirthDate' => $request->input('birthDate'),
            'PhoneNo' => $request->input('phoneNo'),
            'Email' => $request->input('email'),
            'Position' => $request->input('position'),
            'Salary' => $request->input('salary'),
            'StartDate' => $request->input('startDate'),
        ]);

        return redirect(route('employees.index'));
    }

    public function show(string $id): Response
    {
        return Inertia::render('Admin/Employees/Show', [
            'employee' => Employee::where('EmployeeID', $id)->first(),
            'canDelete' => Order::where('EmployeeID', $id)->first() === null,
        ]);
    }

    public function destroy(string $id): RedirectResponse
    {
        Employee::where('EmployeeID', $id)->delete();

        return redirect(route('employees.index'));
    }
}
