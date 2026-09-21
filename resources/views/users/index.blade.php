@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Manajemen Pengguna' => null]" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Manajemen Pengguna</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pengguna</a>
    </div>
    <x-alert />
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light"><tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}"><i class="fas fa-trash"></i></button>
                                    <x-modal-confirm id="deleteModal{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" title="Hapus User" message="Yakin hapus {{ $user->name }}?" />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $users->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
