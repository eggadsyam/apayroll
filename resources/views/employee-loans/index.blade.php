@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pinjaman Karyawan' => null]" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Pinjaman Karyawan</h1>
        <a href="{{ route('employee-loans.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pinjaman</a>
    </div>
    <x-alert />
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('employee-loans.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="paid_off" {{ request('status') == 'paid_off' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="employee_id" class="form-select select2">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}" title="Dari Tanggal">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" title="Sampai Tanggal">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th><th>Karyawan</th><th>Tgl Pinjaman</th><th>Jumlah</th><th>Cicilan/Bulan</th><th>Sisa</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $index => $loan)
                            <tr>
                                <td>{{ $loans->firstItem() + $index }}</td><td>{{ $loan->employee->name }}</td><td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                <td>Rp{{ number_format($loan->amount, 0, ',', '.') }}</td><td>Rp{{ number_format($loan->installment, 0, ',', '.') }}</td><td>Rp{{ number_format($loan->remaining_balance, 0, ',', '.') }}</td>
                                <td>@if($loan->status == 'active') <span class="badge bg-warning text-dark">Aktif</span> @else <span class="badge bg-success">Lunas</span> @endif</td>
                                <td>
                                    <a href="{{ route('employee-loans.show', $loan->id) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                                    @if($loan->status == 'active' && $loan->amount == $loan->remaining_balance)
                                    <a href="{{ route('employee-loans.edit', $loan->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $loan->id }}"><i class="fas fa-trash"></i></button>
                                    <x-modal-confirm id="deleteModal{{ $loan->id }}" action="{{ route('employee-loans.destroy', $loan->id) }}" title="Hapus" message="Yakin hapus data ini?" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Belum ada data pinjaman</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $loans->links() }}</div>
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
