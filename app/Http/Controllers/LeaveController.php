<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Notifications\LeaveNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with(['employee', 'leaveType']);

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
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query->where('end_date', '>=', $startDate)
            ->where('start_date', '<=', $endDate);
        $leaves = $query->latest()->paginate(10);
        $employees = Employee::active()->get();
        $departments = Department::all();

        return view('leaves.index', compact('leaves', 'employees', 'departments'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        $leaveTypes = LeaveType::all();

        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $leaveType = LeaveType::find($request->leave_type_id);
                    // Jika Cuti Tahunan, minimal pengajuan adalah H-1 (besok)
                    if ($leaveType && $leaveType->name === 'Cuti Tahunan') {
                        if (Carbon::parse($value)->startOfDay() <= now()->startOfDay()) {
                            $fail('Untuk Cuti Tahunan, tanggal mulai minimal adalah H-1 pengajuan (besok).');
                        }
                    }
                },
            ],
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $validated['days'] = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;
        Leave::create($validated);

        return redirect()->route('leaves.index')->with('success', 'Pengajuan cuti berhasil ditambahkan');
    }

    public function approve(Leave $leaf)
    {
        $user = auth()->user();

        if (in_array($leaf->status, ['pending', 'pending_supervisor'])) {
            $isSupervisorForLeave = $user->hasRole('supervisor') && $user->employee && $user->employee->id == $leaf->employee->supervisor_id;

            if ($isSupervisorForLeave || $user->hasRole('super_admin')) {
                $leaf->update([
                    'status' => 'pending_manager',
                    'supervisor_approved_by' => $user->id,
                ]);

                return back()->with('success', 'Pengajuan cuti disetujui Supervisor, menunggu persetujuan Manager');
            }
        }

        if (in_array($leaf->status, ['pending_manager', 'pending', 'pending_supervisor'])) {
            $isManagerForLeave = $user->hasRole('manager') && $user->employee && $user->employee->department_id == $leaf->employee->department_id;

            if ($isManagerForLeave || $user->hasRole('super_admin')) {
                $leaf->update([
                    'status' => 'pending_hrd',
                    'manager_approved_by' => $user->id,
                ]);

                return back()->with('success', 'Pengajuan cuti disetujui Manager, menunggu persetujuan HRD');
            }
        }

        if ($leaf->status === 'pending_hrd') {
            $leaf->update([
                'status' => 'approved',
                'approved_by' => $user->id,
            ]);

            if ($leaf->employee->user) {
                $leaf->employee->user->notify(new LeaveNotification(
                    'Status Cuti Diperbarui',
                    'Pengajuan cuti anda telah Disetujui.',
                    route('portal.leave')
                ));
            }

            return back()->with('success', 'Pengajuan cuti berhasil disetujui sepenuhnya oleh HRD');
        }

        // If somehow HRD approves directly from pending_manager (e.g., if superadmin or overriding)
        if ($user->hasRole(['hrd', 'super_admin'])) {
            $leaf->update([
                'status' => 'approved',
                'approved_by' => $user->id,
            ]);

            if ($leaf->employee->user) {
                $leaf->employee->user->notify(new LeaveNotification(
                    'Status Cuti Diperbarui',
                    'Pengajuan cuti anda telah Disetujui.',
                    route('portal.leave')
                ));
            }

            return back()->with('success', 'Pengajuan cuti berhasil disetujui sepenuhnya');
        }

        return back()->with('error', 'Tidak dapat memproses persetujuan');
    }

    public function edit(Leave $leaf)
    {
        $leaveTypes = LeaveType::all();

        // The view expects $leave, not $leaf
        return view('leaves.edit', ['leave' => $leaf, 'leaveTypes' => $leaveTypes]);
    }

    public function update(Request $request, Leave $leaf)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request, $leaf) {
                    $leaveType = LeaveType::find($request->leave_type_id);
                    if ($leaveType && $leaveType->name === 'Cuti Tahunan') {
                        $parsedDate = Carbon::parse($value)->startOfDay();
                        // Hanya validasi jika tanggal mulainya diubah dari aslinya
                        if ($parsedDate->notEqualTo(Carbon::parse($leaf->start_date)->startOfDay())) {
                            if ($parsedDate <= now()->startOfDay()) {
                                $fail('Untuk Cuti Tahunan, tanggal mulai minimal adalah H-1 pengajuan (besok).');
                            }
                        }
                    }
                },
            ],
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $validated['days'] = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;
        $leaf->update($validated);

        return redirect()->route('leaves.index')->with('success', 'Pengajuan cuti berhasil diperbarui');
    }

    public function destroy(Leave $leaf)
    {
        $leaf->delete();

        return redirect()->route('leaves.index')->with('success', 'Pengajuan cuti berhasil dihapus');
    }

    public function reject(Leave $leaf)
    {
        $user = auth()->user();
        $isSupervisorForLeave = $user->hasRole('supervisor') && $user->employee && $user->employee->id == $leaf->employee->supervisor_id;
        $isManagerForLeave = $user->hasRole('manager') && $user->employee && $user->employee->department_id == $leaf->employee->department_id;
        $isHrd = $user->hasRole('hrd');
        $isSuperAdmin = $user->hasRole('super_admin');

        $canReject = false;
        if (in_array($leaf->status, ['pending', 'pending_supervisor']) && ($isSupervisorForLeave || $isSuperAdmin)) {
            $canReject = true;
        } elseif (in_array($leaf->status, ['pending', 'pending_supervisor', 'pending_manager']) && ($isManagerForLeave || $isSuperAdmin)) {
            $canReject = true;
        } elseif ($leaf->status === 'pending_hrd' && ($isHrd || $isSuperAdmin)) {
            $canReject = true;
        }

        if ($canReject) {
            $leaf->update(['status' => 'rejected']);

            if ($leaf->employee->user) {
                $leaf->employee->user->notify(new LeaveNotification(
                    'Status Cuti Diperbarui',
                    'Pengajuan cuti anda telah Ditolak.',
                    route('portal.leave')
                ));
            }

            return back()->with('success', 'Pengajuan cuti berhasil ditolak');
        }

        return back()->with('error', 'Tidak memiliki akses untuk menolak cuti ini');
    }
}
