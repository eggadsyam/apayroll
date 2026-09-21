@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Tipe Cuti' => null]" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Tipe Cuti</h1>
        <a href="{{ route('leave-types.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Tipe</a>
    </div>
    <x-alert />
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th><th>Nama</th><th>Maks Hari</th><th>Deskripsi</th><th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveTypes as $index => $type)
                            <tr>
                                <td>{{ $leaveTypes->firstItem() + $index }}</td><td>{{ $type->name }}</td><td>{{ $type->max_days }}</td><td>{{ $type->description }}</td>
                                <td>
                                    <a href="{{ route('leave-types.edit', $type->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $type->id }}"><i class="fas fa-trash"></i></button>
                                    <x-modal-confirm id="deleteModal{{ $type->id }}" action="{{ route('leave-types.destroy', $type->id) }}" title="Hapus Tipe Cuti" message="Yakin hapus {{ $type->name }}?" />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $leaveTypes->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
