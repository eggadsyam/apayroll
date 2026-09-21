<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CooperativeLoanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CooperativeLoanRequestController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        $requests = $employee
            ? CooperativeLoanRequest::where('employee_id', $employee->id)->latest()->paginate(10)
            : CooperativeLoanRequest::where('employee_id', 0)->paginate(10); // Empty paginator

        return view('portal.cooperative-loans.index', compact('requests', 'employee'));
    }

    public function store(Request $request)
    {
        $employee = Auth::user()->employee;
        if (! $employee) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'tenor_months' => 'required|integer|min:1|max:60',
            'notes' => 'nullable|string',
        ]);

        CooperativeLoanRequest::create([
            'employee_id' => $employee->id,
            'amount' => $request->amount,
            'tenor_months' => $request->tenor_months,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan pinjaman koperasi berhasil dibuat.');
    }
}
