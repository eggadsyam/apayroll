<?php

namespace App\Http\Controllers;

use App\Http\Requests\OvertimeRequest;
use App\Models\Employee;
use App\Models\Overtime;
use App\Notifications\OvertimeNotification;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Overtime::with('employee');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $overtimes = $query->latest('date')->paginate(15);
        $employees = Employee::active()->get();

        return view('overtimes.index', compact('overtimes', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        $defaultRate = 20000;

        return view('overtimes.create', compact('employees', 'defaultRate'));
    }

    public function store(OvertimeRequest $request)
    {
        Overtime::create($request->validated());

        return redirect()->route('overtimes.index')->with('success', 'Lembur berhasil ditambahkan');
    }

    public function approve(Overtime $overtime)
    {
        $overtime->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        if ($overtime->employee->user) {
            $overtime->employee->user->notify(new OvertimeNotification(
                'Lembur Disetujui',
                'Data lembur anda pada tanggal '.$overtime->date->format('d M Y').' telah disetujui.',
                route('portal.overtime')
            ));
        }

        return back()->with('success', 'Lembur berhasil disetujui');
    }

    public function edit(Overtime $overtime)
    {
        return view('overtimes.edit', compact('overtime'));
    }

    public function update(Request $request, Overtime $overtime)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $overtime->update($validated);

        return redirect()->route('overtimes.index')->with('success', 'Lembur berhasil diperbarui');
    }

    public function destroy(Overtime $overtime)
    {
        $overtime->delete();

        return redirect()->route('overtimes.index')->with('success', 'Lembur berhasil dihapus');
    }

    public function reject(Overtime $overtime)
    {
        $overtime->update(['status' => 'rejected']);

        if ($overtime->employee->user) {
            $overtime->employee->user->notify(new OvertimeNotification(
                'Lembur Ditolak',
                'Data lembur anda pada tanggal '.$overtime->date->format('d M Y').' telah ditolak.',
                route('portal.overtime')
            ));
        }

        return back()->with('success', 'Lembur berhasil ditolak');
    }
}
