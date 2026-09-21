@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Cuti Karyawan' => null]" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Data Cuti</h1>
        <a href="{{ route('leaves.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Cuti</a>
    </div>
    <x-alert />
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('leaves.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="department_id" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="employee_id" class="form-select select2">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}" title="Dari Tanggal">
                </div>
                <div class="col-md-4">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" title="Sampai Tanggal">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th><th>Karyawan</th><th>Tipe Cuti</th><th>Mulai</th><th>Selesai</th><th>Hari</th><th>Alasan</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $index => $leave)
                            <tr>
                                <td>{{ $leaves->firstItem() + $index }}</td>
                                <td>{{ $leave->employee->name }}</td>
                                <td>{{ $leave->leaveType->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                                <td>{{ $leave->days }}</td>
                                <td>{{ Str::limit($leave->reason, 30) }}</td>
                                <td>
                                    @if($leave->status == 'approved') <span class="badge bg-success">Disetujui</span>
                                    @elseif($leave->status == 'rejected') <span class="badge bg-danger">Ditolak</span>
                                    @elseif($leave->status == 'pending_manager' || $leave->status == 'pending') <span class="badge bg-warning text-dark">Pending Manager</span>
                                    @elseif($leave->status == 'pending_hrd') <span class="badge bg-info text-dark">Pending HRD</span>
                                    @else <span class="badge bg-secondary">{{ $leave->status }}</span> @endif
                                </td>
                                <td>
                                    @if(in_array($leave->status, ['pending', 'pending_manager', 'pending_hrd']))
                                        <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fas fa-edit"></i></a>
                                        @can('leave.approve')
                                            @php
                                                $isManagerForLeave = auth()->user()->hasRole('manager') && auth()->user()->employee && auth()->user()->employee->department_id == $leave->employee->department_id;
                                                $isSuperAdmin = auth()->user()->hasRole('super_admin');
                                                $isHrd = auth()->user()->hasRole('hrd');
                                            @endphp
                                            @if((in_array($leave->status, ['pending', 'pending_manager']) && ($isManagerForLeave || $isSuperAdmin)) || ($leave->status == 'pending_hrd' && ($isHrd || $isSuperAdmin)))
                                                <form action="{{ route('leaves.approve', $leave->id) }}" method="POST" class="d-inline">@csrf <button class="btn btn-sm btn-success mb-1" onclick="return confirm('Setujui?')"><i class="fas fa-check"></i></button></form>
                                                <form action="{{ route('leaves.reject', $leave->id) }}" method="POST" class="d-inline">@csrf <button class="btn btn-sm btn-danger mb-1" onclick="return confirm('Tolak?')"><i class="fas fa-times"></i></button></form>
                                            @endif
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $leaves->appends(request()->query())->links() }}</div>
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
