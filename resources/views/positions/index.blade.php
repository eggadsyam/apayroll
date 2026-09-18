@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Jabatan' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Jabatan</h1>
        <a href="{{ route('positions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jabatan
        </a>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('positions.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari jabatan..." value="{{ request('search') }}">
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
                            <th>Deskripsi</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($positions as $index => $position)
                            <tr>
                                <td>{{ $positions->firstItem() + $index }}</td>
                                <td>{{ $position->name }}</td>
                                <td>{{ $position->description }}</td>
                                <td>
                                    <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $position->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <x-modal-confirm id="deleteModal{{ $position->id }}" action="{{ route('positions.destroy', $position->id) }}" title="Hapus Jabatan" message="Apakah Anda yakin ingin menghapus jabatan {{ $position->name }}?" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $positions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection