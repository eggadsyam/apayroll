@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Karyawan' => route('employees.index'), 'Detail' => null]" />

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    @if($employee->photo)
                        <img src="{{ Storage::url($employee->photo) }}" class="rounded-circle img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Foto {{ $employee->name }}">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; font-size: 64px;">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <h4 class="font-weight-bold">{{ $employee->name }}</h4>
                    <p class="text-muted mb-1">{{ $employee->employee_code }}</p>
                    <p class="mb-2">{{ $employee->department->name ?? '-' }} | {{ $employee->position->name ?? '-' }}</p>
                    <div>
                        @if($employee->status == 'active')
                            <span class="badge bg-success">Aktif</span>
                        @elseif($employee->status == 'inactive')
                            <span class="badge bg-warning text-dark">Nonaktif</span>
                        @else
                            <span class="badge bg-danger">Resign</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Informasi</h6>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pribadi-tab" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab">Data Pribadi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pekerjaan-tab" data-bs-toggle="tab" data-bs-target="#pekerjaan" type="button" role="tab">Data Pekerjaan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gaji-tab" data-bs-toggle="tab" data-bs-target="#gaji" type="button" role="tab">Gaji & Bank</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="pribadi" role="tabpanel">
                            <table class="table table-borderless">
                                <tr><th width="30%">NIK</th><td>: {{ $employee->nik }}</td></tr>
                                <tr><th>Jenis Kelamin</th><td>: {{ $employee->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                                <tr><th>Tempat, Tgl Lahir</th><td>: {{ $employee->birth_place }}, {{ $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('d M Y') : '-' }}</td></tr>
                                <tr><th>Alamat</th><td>: {{ $employee->address ?? '-' }}</td></tr>
                                <tr><th>No. HP</th><td>: {{ $employee->phone }}</td></tr>
                                <tr><th>Email</th><td>: {{ $employee->email }}</td></tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pekerjaan" role="tabpanel">
                            <table class="table table-borderless">
                                <tr><th width="30%">Status Karyawan</th><td>: {{ $employee->employmentStatus->name ?? '-' }}</td></tr>
                                <tr><th>Shift</th><td>: {{ $employee->shift ? $employee->shift->name . ' (' . \Carbon\Carbon::parse($employee->shift->clock_in)->format('H:i') . ' - ' . \Carbon\Carbon::parse($employee->shift->clock_out)->format('H:i') . ')' : '-' }}</td></tr>
                                <tr><th>Tanggal Bergabung</th><td>: {{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d M Y') : '-' }}</td></tr>
                                <tr><th>NPWP</th><td>: {{ $employee->npwp ?? '-' }}</td></tr>
                                <tr><th>BPJS Kesehatan</th><td>: {{ $employee->bpjs_kesehatan ?? '-' }}</td></tr>
                                <tr><th>BPJS TK</th><td>: {{ $employee->bpjs_ketenagakerjaan ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="gaji" role="tabpanel">
                            <table class="table table-borderless">
                                <tr><th width="30%">Gaji Pokok</th><td>: <strong>Rp{{ number_format($employee->basic_salary, 0, ',', '.') }}</strong></td></tr>
                                <tr><th>Bank</th><td>: {{ $employee->bank_name ?? '-' }}</td></tr>
                                <tr><th>No Rekening</th><td>: {{ $employee->bank_account_number ?? '-' }}</td></tr>
                                <tr><th>Atas Nama</th><td>: {{ $employee->bank_account_name ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection