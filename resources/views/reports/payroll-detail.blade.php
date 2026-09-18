@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Laporan' => null, 'Detail Penggajian' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Laporan Detail Penggajian</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('reports.payroll-detail') }}" method="GET" class="row g-3">
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
            <div class="mb-3">
                <a href="{{ route('reports.export', ['type' => 'payroll-detail', 'period_id' => $selectedPeriod->id]) }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</a>
                <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light"><tr><th>Kode</th><th>Nama</th><th>Komponen</th><th>Tipe</th><th>Nominal</th></tr></thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                            @foreach($payroll->details as $index => $detail)
                            <tr>
                                @if($index == 0)
                                <td rowspan="{{ $payroll->details->count() }}">{{ $payroll->employee->employee_code }}</td>
                                <td rowspan="{{ $payroll->details->count() }}">{{ $payroll->employee->name }}</td>
                                @endif
                                <td>{{ $detail->name }}</td>
                                <td>@if($detail->type == 'earning') <span class="badge bg-success">Pemasukan</span> @else <span class="badge bg-danger">Potongan</span> @endif</td>
                                <td>Rp{{ number_format($detail->amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
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
