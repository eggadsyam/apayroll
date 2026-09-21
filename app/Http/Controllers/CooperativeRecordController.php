<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCooperativeRecordRequest;
use App\Http\Requests\UpdateCooperativeRecordRequest;
use App\Models\CooperativeRecord;
use App\Models\Employee;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CooperativeRecordController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cooperative_record.view', only: ['index', 'show']),
            new Middleware('permission:cooperative_record.create', only: ['create', 'store']),
            new Middleware('permission:cooperative_record.edit', only: ['edit', 'update', 'destroy']),
        ];
    }

    public function index()
    {
        $records = CooperativeRecord::with('employee')->latest('date')->paginate(10);

        return view('cooperative-records.index', compact('records'));
    }

    public function create()
    {
        $employees = Employee::active()->get();

        return view('cooperative-records.create', compact('employees'));
    }

    public function store(StoreCooperativeRecordRequest $request)
    {
        CooperativeRecord::create($request->validated());

        return redirect()->route('cooperative-records.index')->with('success', 'Pencatatan koperasi berhasil ditambahkan.');
    }

    public function show(CooperativeRecord $cooperativeRecord)
    {
        return view('cooperative-records.show', compact('cooperativeRecord'));
    }

    public function edit(CooperativeRecord $cooperativeRecord)
    {
        $employees = Employee::active()->get();

        return view('cooperative-records.edit', compact('cooperativeRecord', 'employees'));
    }

    public function update(UpdateCooperativeRecordRequest $request, CooperativeRecord $cooperativeRecord)
    {
        $cooperativeRecord->update($request->validated());

        return redirect()->route('cooperative-records.index')->with('success', 'Pencatatan koperasi berhasil diperbarui.');
    }

    public function destroy(CooperativeRecord $cooperativeRecord)
    {
        $cooperativeRecord->delete();

        return redirect()->route('cooperative-records.index')->with('success', 'Pencatatan koperasi berhasil dihapus.');
    }
}
