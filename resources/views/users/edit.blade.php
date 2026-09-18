@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Manajemen Pengguna' => route('users.index'), 'Edit' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Edit Pengguna</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3"><label class="form-label">Nama <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required></div>
                <div class="mb-3"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required></div>
                <div class="row mb-3">
                    <div class="col-md-6"><label class="form-label">Password <small class="text-muted">(Kosongkan jika tidak diubah)</small></label><input type="password" class="form-control" name="password"></div>
                    <div class="col-md-6"><label class="form-label">Konfirmasi Password</label><input type="password" class="form-control" name="password_confirmation"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select" name="roles[]" required>
                        @foreach($roles as $role) <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option> @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
