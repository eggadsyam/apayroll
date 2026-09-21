<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        $positions = $query->paginate(10);

        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(PositionRequest $request)
    {
        Position::create($request->validated());

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(PositionRequest $request, Position $position)
    {
        $position->update($request->validated());

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Position $position)
    {
        if ($position->employees()->exists()) {
            return back()->with('error', 'Jabatan tidak bisa dihapus karena memiliki karyawan');
        }
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus');
    }
}
