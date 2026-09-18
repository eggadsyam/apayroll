<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::paginate(15);

        return view('leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_days' => 'required|integer|min:0',
        ]);
        LeaveType::create($validated);

        return redirect()->route('leave-types.index')->with('success', 'Tipe cuti berhasil ditambahkan');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_days' => 'required|integer|min:0',
        ]);
        $leaveType->update($validated);

        return redirect()->route('leave-types.index')->with('success', 'Tipe cuti berhasil diperbarui');
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return redirect()->route('leave-types.index')->with('success', 'Tipe cuti berhasil dihapus');
    }
}
