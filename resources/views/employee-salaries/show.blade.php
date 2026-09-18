@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Gaji Karyawan' => route('employee-salaries.index'), 'Kelola' => null]" />
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Karyawan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><th width="30%">Kode</th><td>: {{ $employee->employee_code }}</td></tr>
                        <tr><th>Nama</th><td>: {{ $employee->name }}</td></tr>
                        <tr><th>Departemen</th><td>: {{ $employee->department->name ?? '-' }}</td></tr>
                        <tr><th>Jabatan</th><td>: {{ $employee->position->name ?? '-' }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h5>Gaji Pokok</h5>
                        <h3>Rp{{ number_format($employee->basic_salary, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bg-success text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Tunjangan</div>
                            <div class="h5 mb-0 font-weight-bold">Rp{{ number_format($totalEarning, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card bg-danger text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Potongan</div>
                            <div class="h5 mb-0 font-weight-bold">Rp{{ number_format($totalDeduction, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-minus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Komponen Gaji Aktif</h6>
        </div>
        <div class="card-body">
            <x-alert />
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Komponen</th>
                            <th>Tipe</th>
                            <th>Nominal</th>
                            <th>Berlaku Sejak</th>
                            <th>Berlaku Sampai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employeeComponents as $ec)
                            <tr>
                                <td>{{ $ec->salaryComponent->name }}</td>
                                <td>
                                    @if($ec->salaryComponent->type == 'earning')
                                        <span class="badge bg-success">Pemasukan</span>
                                    @else
                                        <span class="badge bg-danger">Potongan</span>
                                    @endif
                                </td>
                                <td>Rp{{ number_format($ec->amount, 0, ',', '.') }}</td>
                                <td>{{ $ec->effective_date ? \Carbon\Carbon::parse($ec->effective_date)->format('d M Y') : '-' }}</td>
                                <td>{{ $ec->end_date ? \Carbon\Carbon::parse($ec->end_date)->format('d M Y') : '-' }}</td>
                                <td>
                                    <form action="{{ route('employee-salaries.destroy', $ec->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus komponen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Belum ada komponen gaji tambahan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr>
            <h6 class="font-weight-bold">Tambah Komponen Baru</h6>
            <form action="{{ route('employee-salaries.store', $employee->id) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select name="salary_component_id" class="form-select" required>
                            <option value="">Pilih Komponen</option>
                            @foreach($components as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->type == 'earning' ? '+' : '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="amount" class="form-control" placeholder="Nominal (Rp)" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="effective_date" class="form-control" placeholder="Mulai" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control" placeholder="Sampai (Opsional)">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection