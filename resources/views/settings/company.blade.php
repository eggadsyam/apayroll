@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pengaturan' => null, 'Perusahaan' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Perusahaan</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <x-alert />
            <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3"><label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label><input type="text" class="form-control" name="company_name" value="{{ old('company_name', $setting->company_name ?? '') }}" required></div>
                <div class="mb-3"><label class="form-label">Alamat</label><textarea class="form-control" name="address" rows="3">{{ old('address', $setting->address ?? '') }}</textarea></div>
                <div class="row mb-3">
                    <div class="col-md-6"><label class="form-label">No. Telepon</label><input type="text" class="form-control" name="phone" value="{{ old('phone', $setting->phone ?? '') }}"></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ old('email', $setting->email ?? '') }}"></div>
                </div>
                <div class="mb-3"><label class="form-label">NPWP Perusahaan</label><input type="text" class="form-control" name="npwp" value="{{ old('npwp', $setting->npwp ?? '') }}"></div>
                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    @if(!empty($setting->logo)) <div class="mb-2"><img src="{{ Storage::url($setting->logo) }}" class="img-thumbnail" width="150"></div> @endif
                    <input type="file" class="form-control" name="logo" accept="image/*">
                </div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
