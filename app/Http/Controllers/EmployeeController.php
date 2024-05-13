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
        return Inertia::render('Login', [
            'employees' => Employee::whereIn('Position', ['Manager','Cashier'])->get(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $employee = Employee::where('EmployeeID', $request->input('employeeID'))->first();
        $request->session()->put('employee', $employee->toArray());

        return redirect()->intended(route('pos', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->flush();
        return redirect(route('login'));
    }
}
