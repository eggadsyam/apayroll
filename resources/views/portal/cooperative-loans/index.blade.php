@extends('layouts.employee')

@section('title', 'Pengajuan Pinjaman Koperasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengajuan Pinjaman Koperasi</h1>
        @if($employee)
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRequestModal">
            <i class="fas fa-plus me-1"></i> Ajukan Pinjaman
        </button>
        @endif
    </div>

    @if(!$employee)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Akun Anda belum terhubung dengan data karyawan. Anda tidak dapat mengajukan pinjaman koperasi.
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <th>Jumlah</th>
                            <th>Tenor (Bulan)</th>
                            <th>Status</th>
                            <th>Keterangan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($req->amount, 0, ',', '.') }}</td>
                            <td>{{ $req->tenor_months }} Bulan</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $req->admin_notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada riwayat pengajuan.</td>
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

<!-- Modal Ajukan Pinjaman -->
<div class="modal fade" id="createRequestModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('portal.cooperative-loans.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Pengajuan Pinjaman Koperasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jumlah Pinjaman (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" required min="1000" step="1000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tenor / Lama Cicilan (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="tenor_months" class="form-control" required min="1" max="60" value="1">
                        <small class="text-muted">Potongan otomatis akan dibagi sesuai tenor ini.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan / Keterangan</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Sekarang</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
