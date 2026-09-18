<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayrollPeriodRequest;
use App\Models\PayrollPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollPeriodController extends Controller
{
    public function index(Request $request)
    {
        $query = PayrollPeriod::query();

        if ($request->filled('period_id')) {
            $query->where('id', $request->period_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $periods = $query->latest()->paginate(15)->withQueryString();
        $allPeriods = PayrollPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        return view('payroll-periods.index', compact('periods', 'allPeriods'));
    }

    public function show(Request $request, PayrollPeriod $payrollPeriod)
    {
        $search = $request->get('search');
        $period = $payrollPeriod;

        $payrolls = $payrollPeriod->payrolls()
            ->with('employee')
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('payroll-periods.show', compact('period', 'payrolls', 'search'));
    }

    public function create()
    {
        return view('payroll-periods.create');
    }

    public function store(PayrollPeriodRequest $request)
    {
        $data = $request->validated();

        Carbon::setLocale('id');
        $date = Carbon::create($data['year'], $data['month'], 1);
        $data['name'] = 'Payroll '.$date->translatedFormat('F Y');

        PayrollPeriod::create($data);

        return redirect()->route('payroll-periods.index')->with('success', 'Periode payroll berhasil ditambahkan');
    }

    public function edit(PayrollPeriod $payrollPeriod)
    {
        return view('payroll-periods.edit', compact('payrollPeriod'));
    }

    public function update(PayrollPeriodRequest $request, PayrollPeriod $payrollPeriod)
    {
        $payrollPeriod->update($request->validated());

        return redirect()->route('payroll-periods.index')->with('success', 'Periode payroll berhasil diperbarui');
    }

    public function destroy(PayrollPeriod $payrollPeriod)
    {
        if ($payrollPeriod->status !== 'draft') {
            return back()->with('error', 'Hanya periode dengan status draft yang bisa dihapus');
        }
        $payrollPeriod->delete();

        return redirect()->route('payroll-periods.index')->with('success', 'Periode payroll berhasil dihapus');
    }
}
