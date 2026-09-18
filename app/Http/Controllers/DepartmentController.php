<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        $departments = $query->paginate(15);

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil diperbarui');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Departemen tidak bisa dihapus karena memiliki karyawan');
        }
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil dihapus');
    }
}
