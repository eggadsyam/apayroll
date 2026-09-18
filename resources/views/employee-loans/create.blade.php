@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pinjaman' => route('employee-loans.index'), 'Tambah' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Tambah Pinjaman</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('employee-loans.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                    <select class="form-select select2" name="employee_id" required>
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees as $emp) <option value="{{ $emp->id }}">{{ $emp->name }}</option> @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Tanggal Pinjaman <span class="text-danger">*</span></label><input type="date" class="form-control" name="loan_date" value="{{ date('Y-m-d') }}" required></div>
                <div class="mb-3"><label class="form-label">Jumlah Pinjaman (Rp) <span class="text-danger">*</span></label><input type="number" class="form-control" name="amount" required></div>
                <div class="mb-3"><label class="form-label">Total Tenor (Bulan) <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_installments" required></div>
                <div class="mb-3"><label class="form-label">Cicilan per Bulan (Rp) <span class="text-danger">*</span></label><input type="number" class="form-control" name="installment" required></div>
                <div class="mb-3"><label class="form-label">Keterangan</label><textarea class="form-control" name="notes" rows="3"></textarea></div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
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
