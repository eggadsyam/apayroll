<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Imports\EmployeeImport;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\Position;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'employmentStatus']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('employee_code', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }
        if ($request->filled('status_id')) {
            $query->where('employment_status_id', $request->status_id);
        }

        $employees = $query->paginate(10);
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $employmentStatuses = EmploymentStatus::all();
        $shifts = Shift::all();
        $supervisors = Employee::active()->orderBy('name')->get();

        return view('employees.create', compact('departments', 'positions', 'employmentStatuses', 'shifts', 'supervisors'));
    }

    public function store(EmployeeRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'employmentStatus', 'shift', 'salaryComponents.salaryComponent', 'supervisor']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $positions = Position::all();
        $employmentStatuses = EmploymentStatus::all();
        $shifts = Shift::all();
        $supervisors = Employee::active()->where('id', '!=', $employee->id)->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments', 'positions', 'employmentStatuses', 'shifts', 'supervisors'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->payrolls()->exists()) {
            return back()->with('error', 'Karyawan tidak bisa dihapus karena memiliki data payroll');
        }
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus');
    }

    public function importForm()
    {
        return view('employees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|max:2048',
        ]);

        try {
            Excel::import(new EmployeeImport, $request->file('file'));

            return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diimpor');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: '.$e->getMessage());
        }
    }

    public function template()
    {
        $headers = [
            'NIK', 'Nama', 'Jenis_Kelamin', 'Tempat_Lahir', 'Tanggal_Lahir',
            'Alamat', 'No_Telepon', 'Email', 'Departemen', 'Jabatan',
            'Status_Karyawan', 'Tanggal_Bergabung', 'Gaji_Pokok', 'Nama_Bank',
            'Nomor_Rekening', 'Nama_Rekening', 'NPWP', 'BPJS_Kesehatan',
            'BPJS_Ketenagakerjaan', 'Status_Aktif',
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            // Add example row
            fputcsv($file, [
                '1234567890123456', 'John Doe', 'L', 'Jakarta', '1990-01-01',
                'Jl. Contoh No. 123', '081234567890', 'john@example.com', 'IT', 'Staff',
                'Tetap', '2023-01-01', '5000000', 'BCA',
                '1234567890', 'John Doe', '12.345.678.9-012.000', '0001234567890',
                '12345678901', 'Aktif',
            ]);
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Template_Import_Karyawan.csv"',
        ]);
    }
}
