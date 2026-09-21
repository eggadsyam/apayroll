@extends('layouts.app')

@section('title', 'Persetujuan Pinjaman Koperasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Persetujuan Pinjaman Koperasi</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Jumlah</th>
                            <th>Tenor</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                        <tr>
                            <td>{{ $loop->iteration + $requests->firstItem() - 1 }}</td>
                            <td>
                                {{ $req->employee->user->name ?? '-' }} <br>
                                <small class="text-muted">{{ $req->employee->employee_id ?? '-' }}</small>
                            </td>
                            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($req->amount, 0, ',', '.') }}</td>
                            <td>{{ $req->tenor_months }} Bln</td>
                            <td>{{ $req->notes ?? '-' }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    @can('cooperative_loan_request.approve')
                                    <button class="btn btn-sm btn-success mb-1" data-bs-toggle="modal" data-bs-target="#approveModal{{ $req->id }}">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                    <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('cooperative-loan-requests.approve', $req) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Setujui Pengajuan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Anda akan menyetujui pinjaman sebesar <strong>Rp {{ number_format($req->amount, 0, ',', '.') }}</strong> dengan tenor <strong>{{ $req->tenor_months }} bulan</strong>.</p>
                                                        <p class="text-info small"><i class="fas fa-info-circle"></i> Sistem akan otomatis membuat {{ $req->tenor_months }} entri Pencatatan Koperasi untuk dipotong setiap bulan.</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan Admin (Opsional)</label>
                                                            <textarea name="admin_notes" class="form-control" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Setujui & Buat Potongan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('cooperative-loan-requests.reject', $req) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Pengajuan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea name="admin_notes" class="form-control" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data pengajuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
