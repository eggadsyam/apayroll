@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Lembur' => route('overtimes.index'), 'Edit' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Edit Lembur</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('overtimes.update', $overtime->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Karyawan</label>
                    <input type="text" class="form-control" value="{{ $overtime->employee->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" value="{{ old('date', $overtime->date ? $overtime->date->format('Y-m-d') : '') }}" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($overtime->start_time)->format('H:i')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($overtime->end_time)->format('H:i')) }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rate / Jam (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="rate" value="{{ old('rate', $overtime->rate) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea class="form-control" name="notes" rows="3">{{ old('notes', $overtime->notes) }}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
