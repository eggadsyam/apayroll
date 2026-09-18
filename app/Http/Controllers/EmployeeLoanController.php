<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeLoanRequest;
use App\Models\Employee;
use App\Models\EmployeeLoan;
use App\Notifications\LoanNotification;
use Illuminate\Http\Request;

class EmployeeLoanController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeLoan::with('employee');
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query->where('loan_date', '>=', $startDate)
            ->where('loan_date', '<=', $endDate);
        $loans = $query->latest()->paginate(15);

        $employees = Employee::active()->get();

        return view('employee-loans.index', compact('loans', 'employees'));
    }

    public function show(EmployeeLoan $employeeLoan)
    {
        $employeeLoan->load(['employee', 'payments']);

        return view('employee-loans.show', ['loan' => $employeeLoan, 'employeeLoan' => $employeeLoan]);
    }

    public function create()
    {
        $employees = Employee::active()->get();

        return view('employee-loans.create', compact('employees'));
    }

    public function store(EmployeeLoanRequest $request)
    {
        $data = $request->validated();
        $data['remaining_balance'] = $data['amount'];
        EmployeeLoan::create($data);

        return redirect()->route('employee-loans.index')->with('success', 'Pinjaman berhasil ditambahkan');
    }

    public function edit(EmployeeLoan $employeeLoan)
    {
        $employees = Employee::active()->get();

        return view('employee-loans.edit', compact('employeeLoan', 'employees'));
    }

    public function update(EmployeeLoanRequest $request, EmployeeLoan $employeeLoan)
    {
        $oldStatus = $employeeLoan->status;
        $employeeLoan->update($request->validated());

        if ($oldStatus !== $employeeLoan->status && in_array($employeeLoan->status, ['approved', 'rejected'])) {
            if ($employeeLoan->employee->user) {
                $statusText = $employeeLoan->status === 'approved' ? 'Disetujui' : 'Ditolak';
                $employeeLoan->employee->user->notify(new LoanNotification(
                    'Status Pinjaman/Kasbon',
                    'Pengajuan kasbon anda senilai Rp '.number_format($employeeLoan->amount, 0, ',', '.').' telah '.$statusText.'.',
                    route('portal.dashboard')
                ));
            }
        }

        return redirect()->route('employee-loans.index')->with('success', 'Pinjaman berhasil diperbarui');
    }

    public function destroy(EmployeeLoan $employeeLoan)
    {
        $employeeLoan->delete();

        return redirect()->route('employee-loans.index')->with('success', 'Pinjaman berhasil dihapus');
    }
}
