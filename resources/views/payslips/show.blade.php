@extends('layouts.admin')
@section('content')
<style>
@media print { body * { visibility: hidden; } #printableArea, #printableArea * { visibility: visible; } #printableArea { position: absolute; left: 0; top: 0; width: 100%; } }
</style>
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('payslips.index') }}" class="btn btn-secondary">Kembali</a>
        <div>
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
            <a href="{{ route('payslips.pdf', $payroll->id) }}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
        </div>
    </div>
    <div class="card shadow mb-4" id="printableArea">
        <div class="card-body p-5">
            <div class="row mb-4 border-bottom pb-3">
                <div class="col-sm-6">
                    @if(!empty($company->logo))
                        <img src="{{ Storage::url($company->logo) }}" alt="Logo" style="max-height: 60px; margin-bottom: 10px;">
                    @endif
                    <h2 class="mb-0">{{ $company->company_name ?? 'Nama Perusahaan' }}</h2>
                    <p class="text-muted mb-0">{{ $company->address ?? '-' }}</p>
                </div>
                <div class="col-sm-6 text-end">
                    <h4 class="mb-0">SLIP GAJI</h4>
                    <p class="text-muted mb-0">Periode: {{ $payroll->payrollPeriod->name }}</p>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-6">
                    <table class="table table-sm table-borderless">
                        <tr><td width="30%"><strong>NIK</strong></td><td>: {{ $payroll->employee->nik }}</td></tr>
                        <tr><td><strong>Nama</strong></td><td>: {{ $payroll->employee->name }}</td></tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table class="table table-sm table-borderless">
                        <tr><td width="30%"><strong>Departemen</strong></td><td>: {{ $payroll->employee->department->name ?? '-' }}</td></tr>
                        <tr><td><strong>Jabatan</strong></td><td>: {{ $payroll->employee->position->name ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">PENGHASILAN</h6>
                            <table class="table table-sm table-borderless mb-0">
                                @foreach($payroll->details->where('component_type', 'earning') as $d) <tr><td>{{ $d->component_name }}</td><td class="text-end">Rp{{ number_format($d->amount, 0, ',', '.') }}</td></tr> @endforeach
                            </table><hr>
                            <div class="d-flex justify-content-between"><strong>Total Penghasilan</strong><strong>Rp{{ number_format($payroll->gross_salary, 0, ',', '.') }}</strong></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">POTONGAN</h6>
                            <table class="table table-sm table-borderless mb-0">
                                @foreach($payroll->details->where('component_type', 'deduction') as $d) <tr><td>{{ $d->component_name }}</td><td class="text-end">Rp{{ number_format($d->amount, 0, ',', '.') }}</td></tr> @endforeach
                            </table><hr>
                            <div class="d-flex justify-content-between"><strong>Total Potongan</strong><strong>Rp{{ number_format($payroll->total_deduction, 0, ',', '.') }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-light rounded text-center">
                <h5 class="mb-0">TAKE HOME PAY : <strong>Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></h5>
            </div>
            <div class="row mt-5">
                <div class="col-6 text-center"><p class="mb-5">Penerima,</p><br><br><p><strong>{{ $payroll->employee->name }}</strong></p></div>
                <div class="col-6 text-center"><p class="mb-5">Mengetahui,</p><br><br><p><strong>HR/Finance</strong></p></div>
            </div>
        </div>
    </div>
</div>
@endsection
