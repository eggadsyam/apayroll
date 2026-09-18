@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Status Karyawan' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Status Karyawan</h1>
        <a href="{{ route('employment-statuses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Status
        </a>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('employment-statuses.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari status..." value="{{ request('search') }}">
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
                        @forelse($statuses as $index => $status)
                            <tr>
                                <td>{{ $statuses->firstItem() + $index }}</td>
                                <td>{{ $status->name }}</td>
                                <td>{{ $status->description }}</td>
                                <td>
                                    <a href="{{ route('employment-statuses.edit', $status->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $status->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <x-modal-confirm id="deleteModal{{ $status->id }}" action="{{ route('employment-statuses.destroy', $status->id) }}" title="Hapus Status" message="Apakah Anda yakin ingin menghapus status {{ $status->name }}?" />
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
                {{ $statuses->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection