@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Portal Karyawan' => route('portal.dashboard'), 'Data Absensi' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Data Absensi Saya</h1>
    
    <div class="card shadow mb-4">
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
            <div class="mt-3">{{ $attendances->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
