@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Departemen' => route('departments.index'), 'Edit' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Edit Departemen</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('departments.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $department->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $department->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection