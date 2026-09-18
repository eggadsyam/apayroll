@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Manajemen Pengguna' => route('users.index'), 'Tambah' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Tambah Pengguna</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3"><label class="form-label">Nama <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" value="{{ old('name') }}" required></div>
                <div class="mb-3"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" class="form-control" name="email" value="{{ old('email') }}" required></div>
                <div class="row mb-3">
                    <div class="col-md-6"><label class="form-label">Password <span class="text-danger">*</span></label><input type="password" class="form-control" name="password" required></div>
                    <div class="col-md-6"><label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label><input type="password" class="form-control" name="password_confirmation" required></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select" name="roles[]" required>
                        @foreach($roles as $role) <option value="{{ $role->name }}">{{ $role->name }}</option> @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
