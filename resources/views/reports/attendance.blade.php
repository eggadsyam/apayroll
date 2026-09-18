@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Laporan' => null, 'Kehadiran' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Laporan Kehadiran</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('reports.attendance') }}" method="GET" class="row g-3">
                <div class="col-md-3"><input type="date" name="start_date" class="form-control" value="{{ $startDate }}"></div>
                <div class="col-md-3"><input type="date" name="end_date" class="form-control" value="{{ $endDate }}"></div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept) <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            </form>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col"><div class="card bg-success text-white text-center p-3"><h5>Total Hadir</h5><h3>{{ $summary['present'] ?? 0 }}</h3></div></div>
                <div class="col"><div class="card bg-warning text-dark text-center p-3"><h5>Terlambat</h5><h3>{{ $summary['late'] ?? 0 }}</h3></div></div>
                <div class="col"><div class="card bg-info text-white text-center p-3"><h5>Cuti/Izin</h5><h3>{{ $summary['leave'] ?? 0 }}</h3></div></div>
                <div class="col"><div class="card bg-danger text-white text-center p-3"><h5>Absen</h5><h3>{{ $summary['absent'] ?? 0 }}</h3></div></div>
            </div>
            <div class="mb-3">
                <a href="{{ route('reports.export', array_merge(['type' => 'attendance'], request()->all())) }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light"><tr><th>Tanggal</th><th>Karyawan</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Status</th><th>Terlambat (m)</th><th>Jam Kerja</th></tr></thead>
                    <tbody>
                        @foreach($attendances as $att)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td><td>{{ $att->employee->name }}</td>
                            <td>{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i') : '-' }}</td><td>{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('H:i') : '-' }}</td>
                            <td>{{ $att->status }}</td><td>{{ $att->late_minutes ?? 0 }}</td><td>{{ $att->working_hours ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
