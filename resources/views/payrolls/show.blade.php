@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Penggajian' => route('payrolls.index'), 'Detail' => null]" />
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Detail Penggajian</h1>
        <div>
            <a href="{{ route('payslips.show', $payroll->id) }}" class="btn btn-info text-white"><i class="fas fa-eye"></i> Lihat Slip</a>
            <a href="{{ route('payslips.pdf', $payroll->id) }}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Download PDF</a>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Karyawan</h6></div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $payroll->employee->name }} | <strong>Departemen:</strong> {{ $payroll->employee->department->name ?? '-' }} | <strong>Periode:</strong> {{ $payroll->payrollPeriod->name }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white"><h6 class="m-0 font-weight-bold">PENGHASILAN</h6></div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr><td>Gaji Pokok</td><td class="text-end">Rp{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td></tr>
                        @foreach($payroll->details->where('component_type', 'earning') as $d)
                            <tr><td>{{ $d->component_name }}</td><td class="text-end">Rp{{ number_format($d->amount, 0, ',', '.') }}</td></tr>
                        @endforeach
                        <tr><td>Lembur</td><td class="text-end">Rp{{ number_format($payroll->overtime_amount, 0, ',', '.') }}</td></tr>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between"><strong>TOTAL PENGHASILAN</strong><strong>Rp{{ number_format($payroll->gross_salary, 0, ',', '.') }}</strong></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white"><h6 class="m-0 font-weight-bold">POTONGAN</h6></div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        @foreach($payroll->details->where('component_type', 'deduction') as $d)
                            <tr><td>{{ $d->component_name }}</td><td class="text-end">Rp{{ number_format($d->amount, 0, ',', '.') }}</td></tr>
                        @endforeach
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between"><strong>TOTAL POTONGAN</strong><strong>Rp{{ number_format($payroll->total_deduction, 0, ',', '.') }}</strong></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold text-gray-800 mb-0">GAJI BERSIH (TAKE HOME PAY)</h4>
                <h3 class="font-weight-bold text-primary mb-0">Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
