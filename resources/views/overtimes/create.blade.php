@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Lembur' => route('overtimes.index'), 'Tambah' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Tambah Lembur</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('overtimes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                    <select class="form-select select2" name="employee_id" required>
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="start_time" value="{{ old('start_time') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="end_time" value="{{ old('end_time') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rate / Jam (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="rate" value="{{ old('rate', $defaultRate) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
</script>
@endpush
