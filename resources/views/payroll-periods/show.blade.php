@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Periode Penggajian' => route('payroll-periods.index'), 'Detail' => null]" />
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $period->name }}</h6>
            <div>
                @if($period->status == 'draft') <form action="{{ route('payrolls.generate', $period->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-primary">Generate Payroll</button></form>
                @elseif($period->status == 'processing') <form action="{{ route('payrolls.submit', $period->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-warning">Submit untuk Approval</button></form>
                @elseif($period->status == 'waiting_approval') <form action="{{ route('payrolls.approve', $period->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-success">Setujui</button></form> <form action="{{ route('payrolls.reject', $period->id) }}" method="POST" class="d-inline">@csrf<button type="button" class="btn btn-sm btn-danger" onclick="let r = prompt('Alasan penolakan:'); if(r) { let f = this.closest('form'); f.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'reason\' value=\''+r+'\'>'); f.submit(); }">Tolak</button></form>
                @elseif($period->status == 'approved') <form action="{{ route('payrolls.pay', $period->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-info text-white">Tandai Dibayar</button></form>
                @elseif($period->status == 'paid') <a href="{{ route('payslips.bulk-pdf', $period->id) }}" class="btn btn-sm btn-secondary">Download Slip Gaji (Semua)</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <!-- Form Pencarian Karyawan -->
            <form action="{{ route('payroll-periods.show', $period->id) }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / Kode Karyawan" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('payroll-periods.show', $period->id) }}" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light"><tr><th>No</th><th>Kode</th><th>Nama</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Lembur</th><th>Gaji Kotor</th><th>Potongan</th><th>Gaji Bersih</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($payrolls as $index => $payroll)
                            <tr>
                                <td>{{ $index + 1 }}</td><td>{{ $payroll->employee->employee_code }}</td><td>{{ $payroll->employee->name }}</td>
                                <td>Rp{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td><td>Rp{{ number_format($payroll->total_earning, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($payroll->overtime_amount, 0, ',', '.') }}</td><td>Rp{{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($payroll->total_deduction, 0, ',', '.') }}</td><td><strong>Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
                                <td><a href="{{ route('payrolls.show', $payroll->id) }}" class="btn btn-sm btn-info text-white">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center">Belum ada data penggajian. Silakan generate payroll.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
