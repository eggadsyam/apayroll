<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee.department');

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

        $attendances = $query->latest('date')->paginate(20);
        $employees = Employee::active()->get();

        return view('attendances.index', compact('attendances', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->get();

        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:present,late,sick,permission,annual_leave,absent,holiday',
            'notes' => 'nullable|string',
        ]);

        app(AttendanceService::class)->createAttendance($validated);

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil ditambahkan');
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::active()->get();

        return view('attendances.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:present,late,sick,permission,annual_leave,absent,holiday',
            'notes' => 'nullable|string',
        ]);

        app(AttendanceService::class)->updateAttendance($attendance, $validated);

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil diperbarui');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil dihapus');
    }

    public function importForm()
    {
        return view('attendances.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new AttendanceImport;
            Excel::import($import, $request->file('file'));

            return redirect()->route('attendances.index')
                ->with('success', 'Data absensi berhasil diimport. '.$import->getRowCount().' data diproses.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: '.$e->getMessage());
        }
    }

    public function template()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_absensi.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['kode_karyawan', 'tanggal', 'jam_masuk', 'jam_keluar']);
            // Add a sample row
            fputcsv($file, ['EMP-001', date('Y-m-d'), '08:00', '17:00']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
