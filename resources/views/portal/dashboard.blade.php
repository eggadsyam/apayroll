@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Portal Karyawan' => route('portal.dashboard'), 'Dashboard' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Dashboard Karyawan</h1>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Absensi Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr><th>Tanggal</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $att)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                                    <td>{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i') : '-' }}</td>
                                    <td>{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('H:i') : '-' }}</td>
                                    <td>{{ ucfirst($att->status) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cuti Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr><th>Tipe</th><th>Mulai</th><th>Selesai</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                                    <td>{{ ucfirst($leave->status) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
