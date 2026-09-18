@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Absensi' => route('attendances.index'), 'Import' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Import Data Absensi</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upload File Excel</h6>
                </div>
                <div class="card-body">
                    <x-alert />
                    <form action="{{ route('attendances.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">File Excel/CSV <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="file" accept=".xlsx, .xls, .csv" required>
                            <small class="text-muted">Format file yang didukung: .xlsx, .xls, .csv</small>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Import Data</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Instruksi Import</h6>
                </div>
                <div class="card-body">
                    <p>File Excel/CSV harus memiliki header (baris pertama) dengan nama kolom berikut:</p>
                    <ul>
                        <li><code>kode_karyawan</code> (wajib)</li>
                        <li><code>tanggal</code> (wajib, format: YYYY-MM-DD atau DD/MM/YYYY)</li>
                        <li><code>jam_masuk</code> (format: HH:MM)</li>
                        <li><code>jam_keluar</code> (format: HH:MM)</li>
                    </ul>
                    <a href="{{ route('attendances.template') }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection