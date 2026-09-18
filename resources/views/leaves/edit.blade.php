@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Cuti' => route('leaves.index'), 'Edit' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Edit Pengajuan Cuti</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3"><label class="form-label">Karyawan</label><input type="text" class="form-control" value="{{ $leave->employee->name }}" readonly></div>
                <div class="mb-3">
                    <label class="form-label">Tipe Cuti <span class="text-danger">*</span></label>
                    <select class="form-select" name="leave_type_id" required>
                        <option value="">Pilih Tipe Cuti</option>
                        @foreach($leaveTypes as $type) <option value="{{ $type->id }}" {{ $leave->leave_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option> @endforeach
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6"><label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label><input type="date" class="form-control" name="start_date" value="{{ old('start_date', $leave->start_date ? $leave->start_date->format('Y-m-d') : '') }}" required></div>
                    <div class="col-md-6"><label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label><input type="date" class="form-control" name="end_date" value="{{ old('end_date', $leave->end_date ? $leave->end_date->format('Y-m-d') : '') }}" required></div>
                </div>
                <div class="mb-3"><label class="form-label">Alasan Cuti <span class="text-danger">*</span></label><textarea class="form-control" name="reason" rows="3" required>{{ $leave->reason }}</textarea></div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
