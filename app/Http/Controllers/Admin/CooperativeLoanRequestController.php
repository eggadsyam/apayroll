<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeLoanRequest;
use App\Models\CooperativeRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class CooperativeLoanRequestController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cooperative_loan_request.view', only: ['index']),
            new Middleware('permission:cooperative_loan_request.approve', only: ['approve', 'reject']),
        ];
    }

    public function index()
    {
        $requests = CooperativeLoanRequest::with('employee.user')->latest()->paginate(10);

        return view('cooperative-loan-requests.index', compact('requests'));
    }

    public function approve(Request $request, CooperativeLoanRequest $loanRequest)
    {
        $request->validate(['admin_notes' => 'nullable|string']);

        if ($loanRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Status pengajuan sudah tidak valid.');
        }

        DB::transaction(function () use ($loanRequest, $request) {
            $loanRequest->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes,
            ]);

            // Auto-generate cooperative records based on tenor
            $monthlyInstallment = round($loanRequest->amount / $loanRequest->tenor_months, 2);
            $startDate = Carbon::now()->startOfMonth()->addMonth(); // start next month

            for ($i = 0; $i < $loanRequest->tenor_months; $i++) {
                CooperativeRecord::create([
                    'employee_id' => $loanRequest->employee_id,
                    'date' => $startDate->copy()->addMonths($i)->format('Y-m-d'),
                    'amount' => $monthlyInstallment,
                    'notes' => 'Cicilan Pinjaman Koperasi ('.($i + 1).'/'.$loanRequest->tenor_months.')',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Pengajuan disetujui dan potongan bulanan otomatis dibuat.');
    }

    public function reject(Request $request, CooperativeLoanRequest $loanRequest)
    {
        $request->validate(['admin_notes' => 'required|string']);

        if ($loanRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Status pengajuan sudah tidak valid.');
        }

        $loanRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Pengajuan ditolak.');
    }
}
