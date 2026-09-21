@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Portal Karyawan' => route('portal.dashboard'), 'Cuti & Izin' => null]" />
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cuti & Izin Saya</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leaveModal">
            <i class="fas fa-plus me-1"></i> Ajukan Cuti
        </button>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Tipe</th><th>Mulai</th><th>Selesai</th><th>Alasan</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr>
                            <td>{{ $leave->leaveType->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td>
                                @if($leave->status === 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($leave->status === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $leaves->appends(request()->query())->links() }}</div>
        </div>
    </div>

    <!-- Modal Pengajuan Cuti -->
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveModalLabel">Pengajuan Cuti / Izin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('portal.leave.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tipe Cuti</label>
                            <select name="leave_type_id" class="form-select" required>
                                <option value="">Pilih Tipe Cuti</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
