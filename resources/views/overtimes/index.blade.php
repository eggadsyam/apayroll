@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Lembur' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Data Lembur</h1>
        <a href="{{ route('overtimes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Lembur
        </a>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('overtimes.index') }}" method="GET" class="row g-3">
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
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
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
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Karyawan</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Durasi (jam)</th>
                            <th>Rate/Jam</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimes as $index => $overtime)
                            <tr>
                                <td>{{ $overtimes->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($overtime->date)->format('d/m/Y') }}</td>
                                <td>{{ $overtime->employee->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($overtime->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($overtime->end_time)->format('H:i') }}</td>
                                <td>{{ $overtime->hours }}</td>
                                <td>Rp{{ number_format($overtime->rate, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($overtime->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($overtime->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($overtime->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($overtime->status == 'pending')
                                        <a href="{{ route('overtimes.edit', $overtime->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fas fa-edit"></i></a>
                                        @can('overtime.approve')
                                            <form action="{{ route('overtimes.approve', $overtime->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success mb-1" onclick="return confirm('Setujui lembur?')"><i class="fas fa-check"></i></button>
                                            </form>
                                            <form action="{{ route('overtimes.reject', $overtime->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-danger mb-1" onclick="return confirm('Tolak lembur?')"><i class="fas fa-times"></i></button>
                                            </form>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center">Belum ada data lembur</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $overtimes->links() }}
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