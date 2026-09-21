@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Portal Karyawan' => route('portal.dashboard'), 'Slip Gaji' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Slip Gaji Saya</h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Periode</th><th>Take Home Pay</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $pr)
                        <tr>
                            <td>{{ $pr->payrollPeriod->month_year ?? '-' }}</td>
                            <td>Rp{{ number_format($pr->net_salary, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($pr->status) }}</td>
                            <td>
                                @if($pr->status === 'paid')
                                <a href="{{ route('portal.payslip.pdf', $pr->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Download PDF</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $payrolls->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
