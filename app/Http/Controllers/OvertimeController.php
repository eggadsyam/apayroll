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
        $user = auth()->user();

        if ($user->hasRole('supervisor') && ! $user->hasRole('super_admin') && ! $user->hasRole('hrd') && ! $user->hasRole('manager')) {
            $query->whereHas('employee', function ($q) use ($user) {
                if ($user->employee) {
                    $q->where('supervisor_id', $user->employee->id);
                }
            });
        } elseif ($user->hasRole('manager') && ! $user->hasRole('super_admin') && ! $user->hasRole('hrd')) {
            $query->whereHas('employee', function ($q) use ($user) {
                if ($user->employee) {
                    $q->where('department_id', $user->employee->department_id);
                }
            });
        }

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

        $overtimes = $query->latest('date')->paginate(10);
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
        $user = auth()->user();

        if (in_array($overtime->status, ['pending', 'pending_supervisor'])) {
            $isSupervisorForLeave = $user->hasRole('supervisor') && $user->employee && $user->employee->id == $overtime->employee->supervisor_id;

            if ($isSupervisorForLeave || $user->hasRole('super_admin')) {
                $overtime->update([
                    'status' => 'pending_manager',
                    'supervisor_approved_by' => $user->id,
                ]);

                return back()->with('success', 'Lembur disetujui Supervisor, menunggu persetujuan Manager');
            }
        }

        if (in_array($overtime->status, ['pending_manager', 'pending', 'pending_supervisor'])) {
            $isManagerForLeave = $user->hasRole('manager') && $user->employee && $user->employee->department_id == $overtime->employee->department_id;

            if ($isManagerForLeave || $user->hasRole('super_admin')) {
                $overtime->update([
                    'status' => 'pending_hrd',
                    'manager_approved_by' => $user->id,
                ]);

                return back()->with('success', 'Lembur disetujui Manager, menunggu persetujuan HRD');
            }
        }

        if ($overtime->status === 'pending_hrd' || $user->hasRole(['hrd', 'super_admin'])) {
            $overtime->update([
                'status' => 'approved',
                'approved_by' => $user->id,
            ]);

            if ($overtime->employee->user) {
                $overtime->employee->user->notify(new OvertimeNotification(
                    'Lembur Disetujui',
                    'Data lembur anda pada tanggal '.$overtime->date->format('d M Y').' telah disetujui.',
                    route('portal.overtime')
                ));
            }

            return back()->with('success', 'Lembur berhasil disetujui');
        }

        return back()->with('error', 'Tidak dapat memproses persetujuan');
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
        $user = auth()->user();
        $isSupervisorForLeave = $user->hasRole('supervisor') && $user->employee && $user->employee->id == $overtime->employee->supervisor_id;
        $isManagerForLeave = $user->hasRole('manager') && $user->employee && $user->employee->department_id == $overtime->employee->department_id;
        $isHrd = $user->hasRole('hrd');
        $isSuperAdmin = $user->hasRole('super_admin');

        $canReject = false;
        if (in_array($overtime->status, ['pending', 'pending_supervisor']) && ($isSupervisorForLeave || $isSuperAdmin)) {
            $canReject = true;
        } elseif (in_array($overtime->status, ['pending', 'pending_supervisor', 'pending_manager']) && ($isManagerForLeave || $isSuperAdmin)) {
            $canReject = true;
        } elseif ($overtime->status === 'pending_hrd' && ($isHrd || $isSuperAdmin)) {
            $canReject = true;
        }

        if ($canReject) {
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

        return back()->with('error', 'Tidak memiliki akses untuk menolak lembur ini');
    }
}
