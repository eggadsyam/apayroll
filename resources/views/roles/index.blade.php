@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Manajemen Role' => null]" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Manajemen Role</h1>
        <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Role</a>
    </div>
    <x-alert />
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light"><tr><th width="5%">No</th><th>Nama Role</th><th>Permissions</th><th width="15%">Aksi</th></tr></thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                            <tr>
                                <td>{{ $roles->firstItem() + $index }}</td><td>{{ $role->name }}</td>
                                <td>
                                    @foreach($role->permissions->take(5) as $p) <span class="badge bg-secondary">{{ $p->name }}</span> @endforeach
                                    @if($role->permissions->count() > 5) <span class="badge bg-info">+{{ $role->permissions->count() - 5 }} lainnya</span> @endif
                                </td>
                                <td>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $role->id }}"><i class="fas fa-trash"></i></button>
                                    <x-modal-confirm id="deleteModal{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" title="Hapus Role" message="Yakin hapus role {{ $role->name }}?" />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $roles->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
