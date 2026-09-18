@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Laporan' => null, 'Lembur' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Laporan Lembur</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('reports.overtime') }}" method="GET" class="row g-3">
                <div class="col-md-4"><input type="month" name="month_year" class="form-control" value="{{ request('month_year', date('Y-m')) }}"></div>
                <div class="col-md-4"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            </form>
        </div>
        <div class="card-body">
            <div class="mb-3"><a href="{{ route('reports.export', array_merge(['type' => 'overtime'], request()->all())) }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</a></div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light"><tr><th>Karyawan</th><th>Total Jam Lembur</th><th>Total Nominal Lembur</th></tr></thead>
                    <tbody>
                        @foreach($overtimes as $ot)
                        <tr><td>{{ $ot->employee->name }}</td><td>{{ $ot->total_hours }} Jam</td><td>Rp{{ number_format($ot->total_amount, 0, ',', '.') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
