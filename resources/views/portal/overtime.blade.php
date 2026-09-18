@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Portal Karyawan' => route('portal.dashboard'), 'Lembur' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Riwayat Lembur Saya</h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Jam Mulai</th><th>Jam Selesai</th><th>Total Jam</th><th>Keterangan</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($overtimes as $ot)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($ot->date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ot->start_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ot->end_time)->format('H:i') }}</td>
                            <td>{{ $ot->hours }} Jam</td>
                            <td>{{ $ot->notes }}</td>
                            <td>{{ ucfirst($ot->status) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $overtimes->links() }}</div>
        </div>
    </div>
</div>
@endsection
