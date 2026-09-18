@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Komponen Gaji' => route('salary-components.index'), 'Tambah' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Tambah Komponen Gaji</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('salary-components.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select" name="type" required>
                        <option value="earning" {{ old('type') == 'earning' ? 'selected' : '' }}>Pemasukan (Earning)</option>
                        <option value="deduction" {{ old('type') == 'deduction' ? 'selected' : '' }}>Potongan (Deduction)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe Perhitungan <span class="text-danger">*</span></label>
                    <select class="form-select" name="calculation_type" required>
                        <option value="fixed" {{ old('calculation_type') == 'fixed' ? 'selected' : '' }}>Tetap (Fixed)</option>
                        <option value="percentage" {{ old('calculation_type') == 'percentage' ? 'selected' : '' }}>Persentase (Percentage)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal Default (Rp) / Persentase <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" name="default_amount" value="{{ old('default_amount', 0) }}" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="taxable" name="taxable" value="1" {{ old('taxable') ? 'checked' : '' }}>
                    <label class="form-check-label" for="taxable">Kena Pajak</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
