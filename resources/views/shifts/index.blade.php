@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Shift' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Shift</h1>
        <a href="{{ route('shifts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Shift
        </a>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('shifts.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Shift..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $index => $shift)
                            <tr>
                                <td>{{ $shifts->firstItem() + $index }}</td>
                                <td>{{ $shift->name }}</td>
                                <td>{{ $shift->clock_in }}</td>
                                <td>{{ $shift->clock_out }}</td>
                                <td>
                                    <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $shift->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <x-modal-confirm id="deleteModal{{ $shift->id }}" action="{{ route('shifts.destroy', $shift->id) }}" title="Hapus Shift" message="Apakah Anda yakin ingin menghapus Shift {{ $shift->name }}?" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $shifts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
