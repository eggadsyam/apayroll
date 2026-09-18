@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Absensi' => route('attendances.index'), 'Edit' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Edit Absensi</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Karyawan</label>
                    <input type="text" class="form-control" value="{{ $attendance->employee->employee_code }} - {{ $attendance->employee->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" value="{{ old('date', $attendance->date ? $attendance->date->format('Y-m-d') : '') }}" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" class="form-control" name="clock_in" value="{{ old('clock_in', $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Keluar</label>
                        <input type="time" class="form-control" name="clock_out" value="{{ old('clock_out', $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absen</option>
                        <option value="leave" {{ old('status', $attendance->status) == 'leave' ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea class="form-control" name="notes" rows="2">{{ old('notes', $attendance->notes) }}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection