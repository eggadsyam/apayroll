@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Absensi' => route('attendances.index'), 'Tambah' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Tambah Absensi Manual</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('attendances.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                    <select class="form-select select2" name="employee_id" required>
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->employee_code }} - {{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" class="form-control" name="clock_in" value="{{ old('clock_in') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Keluar</label>
                        <input type="time" class="form-control" name="clock_out" value="{{ old('clock_out') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absen</option>
                        <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih Karyawan'
        });
    });
</script>
@endpush