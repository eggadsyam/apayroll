@extends('layouts.app')

@section('title', 'Pencatatan Koperasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pencatatan Koperasi</h1>
        @can('cooperative_record.create')
        <a href="{{ route('cooperative-records.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Catatan
        </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Karyawan</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration + $records->firstItem() - 1 }}</td>
                            <td>{{ $record->date->format('d/m/Y') }}</td>
                            <td>
                                {{ $record->employee->user->name ?? '-' }} <br>
                                <small class="text-muted">{{ $record->employee->employee_id ?? '-' }}</small>
                            </td>
                            <td>Rp {{ number_format($record->amount, 0, ',', '.') }}</td>
                            <td>{{ $record->notes ?? '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('cooperative_record.edit')
                                    <a href="{{ route('cooperative-records.edit', $record) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('cooperative-records.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pencatatan koperasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $records->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
