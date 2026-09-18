@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pengaturan' => null, 'Penggajian' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Penggajian & Absensi</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <x-alert />
            <form action="{{ route('settings.payroll.update') }}" method="POST">
                @csrf @method('PUT')
                <h5 class="mb-3 text-primary">Regulasi & Asuransi</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Capping BPJS Kesehatan (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="bpjs_kesehatan_capping" value="{{ old('bpjs_kesehatan_capping', $setting->bpjs_kesehatan_capping ?? 12000000) }}" required>
                        <small class="text-muted">Batas maksimal gaji basis perhitungan BPJS Kes</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Capping BPJS JP (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="bpjs_jp_capping" value="{{ old('bpjs_jp_capping', $setting->bpjs_jp_capping ?? 10042300) }}" required>
                        <small class="text-muted">Batas maksimal gaji basis perhitungan JP Ketenagakerjaan</small>
                    </div>
                </div>
                
                <h5 class="mb-3 mt-4 text-primary">Pengaturan Lembur & Keterlambatan</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Formula Lembur <span class="text-danger">*</span></label>
                        <select class="form-select" name="overtime_formula" required>
                            <option value="flat" {{ old('overtime_formula', $setting->overtime_formula ?? 'flat') == 'flat' ? 'selected' : '' }}>Flat Rate (Rupiah per Jam)</option>
                            <option value="depnaker" {{ old('overtime_formula', $setting->overtime_formula ?? 'flat') == 'depnaker' ? 'selected' : '' }}>Standar Depnaker (1/173 x Gaji)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rate Lembur per Jam (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="overtime_rate_per_hour" value="{{ old('overtime_rate_per_hour', $setting->overtime_rate_per_hour ?? 0) }}" required>
                        <small class="text-muted">Hanya berlaku jika Formula Lembur = Flat Rate</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Denda Keterlambatan per Menit (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="late_penalty_per_minute" value="{{ old('late_penalty_per_minute', $setting->late_penalty_per_minute ?? 0) }}" required>
                </div>

                <h5 class="mb-3 mt-4 text-primary">Standar Jam Kerja</h5>
                <div class="mb-3">
                    <label class="form-label">Jam Kerja per Hari <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="working_hours_per_day" value="{{ old('working_hours_per_day', $setting->working_hours_per_day ?? 8) }}" required>
                    <small class="text-muted">Standar jam kerja per hari untuk perhitungan lembur</small>
                </div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Pengaturan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
