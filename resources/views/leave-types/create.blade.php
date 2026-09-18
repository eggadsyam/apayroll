@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Tipe Cuti' => route('leave-types.index'), 'Tambah' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Tambah Tipe Cuti</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('leave-types.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Maks Hari per Tahun <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="max_days" value="{{ old('max_days') }}" required min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
