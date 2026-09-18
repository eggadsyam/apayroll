@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Absensi' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Data Absensi</h1>
        <div>
            <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Manual
            </a>
            <a href="{{ route('attendances.import') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Import Excel
            </a>
        </div>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('attendances.index') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
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
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absen</option>
                        <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Terlambat (m)</th>
                            <th>Jam Kerja</th>
                            <th>Lembur (j)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                            <tr>
                                <td>{{ $attendances->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                <td>{{ $attendance->employee->employee_code }}</td>
                                <td>{{ $attendance->employee->name }}</td>
                                <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                                <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                                <td>
                                    @if($attendance->status == 'present')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="badge bg-warning text-dark">Terlambat</span>
                                    @elseif($attendance->status == 'leave')
                                        <span class="badge bg-info">Cuti</span>
                                    @else
                                        <span class="badge bg-danger">Absen</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->late_minutes ?? 0 }}</td>
                                <td>{{ $attendance->working_hours ?? 0 }}</td>
                                <td>{{ $attendance->overtime_hours ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center">Belum ada data absensi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $attendances->appends(request()->query())->links() }}
            </div>
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
            placeholder: 'Semua Karyawan'
        });
    });
</script>
@endpush