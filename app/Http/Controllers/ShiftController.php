<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Shift::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        $shifts = $query->paginate(10);

        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'required|date_format:H:i',
        ]);

        if (strlen($validated['clock_in']) == 5) {
            $validated['clock_in'] .= ':00';
        }
        if (strlen($validated['clock_out']) == 5) {
            $validated['clock_out'] .= ':00';
        }

        Shift::create($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditambahkan');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'required|date_format:H:i',
        ]);

        if (strlen($validated['clock_in']) == 5) {
            $validated['clock_in'] .= ':00';
        }
        if (strlen($validated['clock_out']) == 5) {
            $validated['clock_out'] .= ':00';
        }

        $shift->update($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil diperbarui');
    }

    public function destroy(Shift $shift)
    {
        if ($shift->employees()->exists()) {
            return back()->with('error', 'Shift tidak bisa dihapus karena digunakan oleh karyawan');
        }
        $shift->delete();

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil dihapus');
    }
}
