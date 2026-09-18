<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmploymentStatusRequest;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;

class EmploymentStatusController extends Controller
{
    public function index(Request $request)
    {
        $query = EmploymentStatus::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        $statuses = $query->paginate(15);

        return view('employment-statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('employment-statuses.create');
    }

    public function store(EmploymentStatusRequest $request)
    {
        EmploymentStatus::create($request->validated());

        return redirect()->route('employment-statuses.index')->with('success', 'Status karyawan berhasil ditambahkan');
    }

    public function edit(EmploymentStatus $employmentStatus)
    {
        return view('employment-statuses.edit', compact('employmentStatus'));
    }

    public function update(EmploymentStatusRequest $request, EmploymentStatus $employmentStatus)
    {
        $employmentStatus->update($request->validated());

        return redirect()->route('employment-statuses.index')->with('success', 'Status karyawan berhasil diperbarui');
    }

    public function destroy(EmploymentStatus $employmentStatus)
    {
        if ($employmentStatus->employees()->exists()) {
            return back()->with('error', 'Status karyawan tidak bisa dihapus karena digunakan oleh karyawan');
        }
        $employmentStatus->delete();

        return redirect()->route('employment-statuses.index')->with('success', 'Status karyawan berhasil dihapus');
    }
}
