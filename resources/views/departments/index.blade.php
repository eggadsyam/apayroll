@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Departemen' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Departemen</h1>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Departemen
        </a>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('departments.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari departemen..." value="{{ request('search') }}">
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
                        @forelse($departments as $index => $department)
                            <tr>
                                <td>{{ $departments->firstItem() + $index }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->description }}</td>
                                <td>
                                    <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $department->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <x-modal-confirm id="deleteModal{{ $department->id }}" action="{{ route('departments.destroy', $department->id) }}" title="Hapus Departemen" message="Apakah Anda yakin ingin menghapus departemen {{ $department->name }}?" />
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
                {{ $departments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection