@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Shift' => route('shifts.index'), 'Edit' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Edit Shift</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('shifts.update', $shift->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $shift->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="clock_in" class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('clock_in') is-invalid @enderror" id="clock_in" name="clock_in" value="{{ old('clock_in', \Carbon\Carbon::parse($shift->clock_in)->format('H:i')) }}" required>
                        @error('clock_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="clock_out" class="form-label">Jam Keluar <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('clock_out') is-invalid @enderror" id="clock_out" name="clock_out" value="{{ old('clock_out', \Carbon\Carbon::parse($shift->clock_out)->format('H:i')) }}" required>
                        @error('clock_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('shifts.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection