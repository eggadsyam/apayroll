@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Laporan' => null, 'Data Karyawan' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Laporan Data Karyawan</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('reports.employee') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="department_id" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept) <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Resign</option>
                    </select>
                </div>
                <div class="col-md-4"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            </form>
        </div>
        <div class="card-body">
            <div class="mb-3"><a href="{{ route('reports.export', array_merge(['type' => 'employee'], request()->all())) }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</a></div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light"><tr><th>Kode</th><th>NIK</th><th>Nama</th><th>Departemen</th><th>Jabatan</th><th>Tgl Bergabung</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr><td>{{ $emp->employee_code }}</td><td>{{ $emp->nik }}</td><td>{{ $emp->name }}</td><td>{{ $emp->department->name ?? '-' }}</td><td>{{ $emp->position->name ?? '-' }}</td><td>{{ \Carbon\Carbon::parse($emp->join_date)->format('d/m/Y') }}</td><td>{{ ucfirst($emp->status) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
