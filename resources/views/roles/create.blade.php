@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Manajemen Role' => route('roles.index'), 'Tambah' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Tambah Role</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-3"><label class="form-label">Nama Role <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" value="{{ old('name') }}" required></div>
                <div class="mb-3">
                    <label class="form-label">Permissions <span class="text-danger">*</span></label>
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
