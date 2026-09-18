@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Laporan' => null, 'Ringkasan Penggajian' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Laporan Ringkasan Penggajian</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('reports.payroll-summary') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="period_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Pilih Periode</option>
                        @foreach($periods as $p) <option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option> @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if(isset($selectedPeriod))
            <div class="row mb-4">
                <div class="col-md-3"><div class="card border-left-primary shadow h-100 py-2"><div class="card-body"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Karyawan</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $payrolls->count() }}</div></div></div></div>
                <div class="col-md-3"><div class="card border-left-success shadow h-100 py-2"><div class="card-body"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Gaji Kotor</div><div class="h5 mb-0 font-weight-bold text-gray-800">Rp{{ number_format($payrolls->sum('gross_salary'), 0, ',', '.') }}</div></div></div></div>
                <div class="col-md-3"><div class="card border-left-danger shadow h-100 py-2"><div class="card-body"><div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Potongan</div><div class="h5 mb-0 font-weight-bold text-gray-800">Rp{{ number_format($payrolls->sum('total_deduction'), 0, ',', '.') }}</div></div></div></div>
                <div class="col-md-3"><div class="card border-left-info shadow h-100 py-2"><div class="card-body"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Gaji Bersih</div><div class="h5 mb-0 font-weight-bold text-gray-800">Rp{{ number_format($payrolls->sum('net_salary'), 0, ',', '.') }}</div></div></div></div>
            </div>
            <div class="mb-3">
                <a href="{{ route('reports.export', 'payroll-summary') }}?period_id={{ $selectedPeriod->id }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</a>
                <a href="{{ route('reports.export', 'payroll-summary-pdf') }}?period_id={{ $selectedPeriod->id }}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Export PDF</a>
                <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light"><tr><th>No</th><th>Kode</th><th>Nama</th><th>Dept</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Lembur</th><th>Gaji Kotor</th><th>Potongan</th><th>Gaji Bersih</th></tr></thead>
                    <tbody>
                        @foreach($payrolls as $index => $payroll)
                        <tr>
                            <td>{{ $index + 1 }}</td><td>{{ $payroll->employee->employee_code }}</td><td>{{ $payroll->employee->name }}</td><td>{{ $payroll->employee->department->name ?? '-' }}</td>
                            <td>{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td><td>{{ number_format($payroll->total_allowance, 0, ',', '.') }}</td><td>{{ number_format($payroll->total_overtime, 0, ',', '.') }}</td>
                            <td>{{ number_format($payroll->gross_salary, 0, ',', '.') }}</td><td>{{ number_format($payroll->total_deduction, 0, ',', '.') }}</td><td><strong>{{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center">Pilih periode untuk melihat laporan.</p>
            @endif
        </div>
    </div>
</div>
@endsection
