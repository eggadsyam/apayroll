<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalaryComponent;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $employees = Employee::active()
            ->with(['department', 'position', 'salaryComponents' => function ($q) {
                $q->active();
            }, 'salaryComponents.salaryComponent'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->paginate(15)
            ->withQueryString();

        $employees->getCollection()->transform(function ($employee) {
            $employee->total_earnings = $employee->salaryComponents->filter(function ($ec) {
                return $ec->salaryComponent && $ec->salaryComponent->type === 'earning';
            })->sum('amount');

            $employee->total_deductions = $employee->salaryComponents->filter(function ($ec) {
                return $ec->salaryComponent && $ec->salaryComponent->type === 'deduction';
            })->sum('amount');

            return $employee;
        });

        return view('employee-salaries.index', compact('employees', 'search'));
    }

    public function show(Employee $employee)
    {
        $employee->load(['salaryComponents' => function ($q) {
            $q->active();
        }, 'salaryComponents.salaryComponent']);

        $employeeComponents = $employee->salaryComponents;

        $totalEarning = $employeeComponents->filter(function ($ec) {
            return $ec->salaryComponent && $ec->salaryComponent->type === 'earning';
        })->sum('amount');

        $totalDeduction = $employeeComponents->filter(function ($ec) {
            return $ec->salaryComponent && $ec->salaryComponent->type === 'deduction';
        })->sum('amount');

        $components = SalaryComponent::active()->orderBy('sort_order')->get();

        return view('employee-salaries.show', compact('employee', 'components', 'employeeComponents', 'totalEarning', 'totalDeduction'));
    }

    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'salary_component_id' => 'required|exists:salary_components,id',
            'amount' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
        ]);

        $employee->salaryComponents()->create($validated);

        return back()->with('success', 'Komponen gaji berhasil ditambahkan');
    }

    public function destroy(EmployeeSalaryComponent $employeeSalaryComponent)
    {
        $employeeSalaryComponent->delete();

        return back()->with('success', 'Komponen gaji berhasil dihapus');
    }
}
