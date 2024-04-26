<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render("Admin/Employees/Index", [
            "employees" => Employee::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render("Admin/Employees/Create");
    }

    public function store(Request $request): RedirectResponse
    {
        Employee::create([
            "FirstName" => $request->firstName,
            "LastName" => $request->lastName,
            "BirthDate" => $request->birthDate,
            "PhoneNo" => $request->phoneNo,
            "Email" => $request->email,
            "Position" => $request->position,
            "Salary" => $request->salary,
            "StartDate" => $request->startDate,
        ]);

        return redirect(route("employees.index"));
    }

    public function show(string $id): Response
    {
        return Inertia::render("Admin/Employees/Show", [
            "employee" => Employee::where("EmployeeID", $id)->first(),
        ]);
    }

    public function logins(): Response
    {
        return Inertia::render("Login", [
            "employees" => Employee::whereIn("Position", [
                "Manager",
                "Cashier",
            ])->get(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $employee = Employee::where(
            "EmployeeID",
            $request->employeeID
        )->first();
        $request->session()->put("employee", $employee->toArray());

        return redirect()->intended(route("pos", absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->flush();

        return redirect(route("logins"));
    }
}
