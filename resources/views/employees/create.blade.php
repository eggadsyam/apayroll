@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Karyawan' => route('employees.index'), 'Tambah' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Tambah Karyawan</h1>

    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pribadi</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Karyawan</label>
                            <input type="text" class="form-control" name="employee_code" value="{{ old('employee_code', 'Otomatis') }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik') }}" required>
                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" name="birth_place" value="{{ old('birth_place') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">No. HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pekerjaan</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Departemen <span class="text-danger">*</span></label>
                            <select class="form-select @error('department_id') is-invalid @enderror" name="department_id" required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supervisor / Atasan Langsung</label>
                            <select class="form-select @error('supervisor_id') is-invalid @enderror" name="supervisor_id">
                                <option value="">Tidak ada supervisor</option>
                                @foreach($supervisors as $sup)
                                    <option value="{{ $sup->id }}" {{ old('supervisor_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                            @error('supervisor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <select class="form-select @error('position_id') is-invalid @enderror" name="position_id" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                @endforeach
                            </select>
                            @error('position_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Karyawan <span class="text-danger">*</span></label>
                            <select class="form-select @error('employment_status_id') is-invalid @enderror" name="employment_status_id" required>
                                <option value="">Pilih Status</option>
                                @foreach($employmentStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('employment_status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('employment_status_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shift <span class="text-danger">*</span></label>
                            <select class="form-select @error('shift_id') is-invalid @enderror" name="shift_id" required>
                                <option value="">Pilih Shift</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>{{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->clock_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->clock_out)->format('H:i') }})</option>
                                @endforeach
                            </select>
                            @error('shift_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Bergabung <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('join_date') is-invalid @enderror" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Aktif <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="resigned" {{ old('status') == 'resigned' ? 'selected' : '' }}>Resign</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Bank</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" value="{{ old('bank_name') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" name="bank_account_number" value="{{ old('bank_account_number') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Pemilik Rekening</label>
                            <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" name="bank_account_name" value="{{ old('bank_account_name') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Lainnya & Gaji</h6>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">NPWP</label>
                            <input type="text" class="form-control @error('npwp') is-invalid @enderror" name="npwp" value="{{ old('npwp') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">BPJS Kesehatan</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <input type="hidden" name="is_bpjs_kesehatan_active" value="0">
                                    <input class="form-check-input mt-0" type="checkbox" name="is_bpjs_kesehatan_active" value="1" {{ old('is_bpjs_kesehatan_active', 1) ? 'checked' : '' }} aria-label="Aktif">
                                </span>
                                <input type="text" class="form-control @error('bpjs_kesehatan') is-invalid @enderror" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan') }}" placeholder="Nomor BPJS Kes">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">BPJS Ketenagakerjaan</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <input type="hidden" name="is_bpjs_ketenagakerjaan_active" value="0">
                                    <input class="form-check-input mt-0" type="checkbox" name="is_bpjs_ketenagakerjaan_active" value="1" {{ old('is_bpjs_ketenagakerjaan_active', 1) ? 'checked' : '' }} aria-label="Aktif">
                                </span>
                                <input type="text" class="form-control @error('bpjs_ketenagakerjaan') is-invalid @enderror" name="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan') }}" placeholder="Nomor BPJS TK">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status PTKP <span class="text-danger">*</span></label>
                            <select class="form-select @error('ptkp_status') is-invalid @enderror" name="ptkp_status" required>
                                @foreach(['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'] as $ptkp)
                                    <option value="{{ $ptkp }}" {{ old('ptkp_status') == $ptkp ? 'selected' : '' }}>{{ $ptkp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Metode PPh 21 <span class="text-danger">*</span></label>
                            <select class="form-select @error('tax_method') is-invalid @enderror" name="tax_method" required>
                                <option value="gross" {{ old('tax_method') == 'gross' ? 'selected' : '' }}>Gross (Dipotong)</option>
                                <option value="gross_up" {{ old('tax_method') == 'gross_up' ? 'selected' : '' }}>Gross Up (Ditanggung)</option>
                                <option value="nett" {{ old('tax_method') == 'nett' ? 'selected' : '' }}>Nett (Tanpa Pajak)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gaji Pokok (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('basic_salary') is-invalid @enderror" name="basic_salary" value="{{ old('basic_salary', 0) }}" required min="0">
                            @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Karyawan</button>
        </div>
    </form>
</div>
@endsection