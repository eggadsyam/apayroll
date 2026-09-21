<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaryComponentRequest;
use App\Models\SalaryComponent;

class SalaryComponentController extends Controller
{
    public function index()
    {
        $components = SalaryComponent::orderBy('sort_order')->paginate(10);

        return view('salary-components.index', compact('components'));
    }

    public function create()
    {
        return view('salary-components.create');
    }

    public function store(SalaryComponentRequest $request)
    {
        SalaryComponent::create($request->validated());

        return redirect()->route('salary-components.index')->with('success', 'Komponen gaji berhasil ditambahkan');
    }

    public function edit(SalaryComponent $salaryComponent)
    {
        return view('salary-components.edit', compact('salaryComponent'));
    }

    public function update(SalaryComponentRequest $request, SalaryComponent $salaryComponent)
    {
        $salaryComponent->update($request->validated());

        return redirect()->route('salary-components.index')->with('success', 'Komponen gaji berhasil diperbarui');
    }

    public function destroy(SalaryComponent $salaryComponent)
    {
        $salaryComponent->delete();

        return redirect()->route('salary-components.index')->with('success', 'Komponen gaji berhasil dihapus');
    }
}
