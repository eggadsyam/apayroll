@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pinjaman' => route('employee-loans.index'), 'Detail' => null]" />
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Pinjaman</h6></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th width="40%">Karyawan</th><td>: {{ $loan->employee->name }}</td></tr>
                        <tr><th>Tanggal Pinjaman</th><td>: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td></tr>
                        <tr><th>Jumlah Pinjaman</th><td>: Rp{{ number_format($loan->amount, 0, ',', '.') }}</td></tr>
                        <tr><th>Cicilan per Bulan</th><td>: Rp{{ number_format($loan->installment, 0, ',', '.') }}</td></tr>
                        <tr><th>Status</th><td>: @if($loan->status == 'active') <span class="badge bg-warning text-dark">Aktif</span> @else <span class="badge bg-success">Lunas</span> @endif</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Progres</h6></div>
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h3 class="font-weight-bold mb-3">Sisa: Rp{{ number_format($loan->remaining_balance, 0, ',', '.') }}</h3>
                    @php $paid = $loan->amount - $loan->remaining_balance; $percent = $loan->amount > 0 ? ($paid / $loan->amount) * 100 : 0; @endphp
                    <div class="progress mb-3" style="height: 25px;"><div class="progress-bar bg-success" style="width: {{ $percent }}%;">{{ round($percent) }}%</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light"><tr><th>No</th><th>Tanggal</th><th>Jumlah</th></tr></thead>
                    <tbody>
                        @forelse($loan->employeeLoanPayments ?? [] as $index => $inst)
                            <tr><td>{{ $index + 1 }}</td><td>{{ \Carbon\Carbon::parse($inst->payment_date)->format('d M Y') }}</td><td>Rp{{ number_format($inst->amount, 0, ',', '.') }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center">Belum ada riwayat pembayaran</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
