@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Karyawan' => route('employees.index'), 'Import' => null]" />

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Import Karyawan</h1>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <x-alert />

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upload File Import</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">File (CSV, XLS, XLSX) <span class="text-danger">*</span></label>
                            <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <p class="text-muted small">
                                Silakan gunakan template yang telah disediakan untuk memastikan format data sesuai dengan yang dibutuhkan oleh sistem.
                            </p>
                            <a href="{{ route('employees.template') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Proses Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Petunjuk Import</h6>
                </div>
                <div class="card-body">
                    <ol class="small">
                        <li>Download template CSV yang telah disediakan.</li>
                        <li>Isi data karyawan sesuai dengan kolom pada template.</li>
                        <li>Pastikan kolom <strong>Nama</strong> dan <strong>Gaji Pokok</strong> terisi (wajib).</li>
                        <li>Untuk kolom relasi seperti <strong>Departemen</strong>, <strong>Jabatan</strong>, dan <strong>Status Karyawan</strong>, pastikan namanya sama persis dengan data master di sistem.</li>
                        <li>Format tanggal menggunakan format <strong>YYYY-MM-DD</strong> (contoh: 1990-12-31).</li>
                        <li>Simpan file dan upload melalui form di samping.</li>
                        <li>Tekan tombol "Proses Import" untuk memasukkan data ke dalam sistem.</li>
                    </ol>
                    <div class="alert alert-info small mt-3 mb-0">
                        <i class="fas fa-info-circle"></i> Jika NIK atau Email sudah terdaftar pada sistem, baris data tersebut akan dilewati atau menyebabkan error pada saat import (bergantung pada validasi).
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
