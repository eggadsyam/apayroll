@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Karyawan' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Karyawan</h1>
        <div>
            <a href="{{ route('employees.import') }}" class="btn btn-success">
                <i class="fas fa-file-import"></i> Import Karyawan
            </a>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
        </div>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('employees.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama/kode..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Resign</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Gaji Pokok</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $index => $employee)
                            <tr>
                                <td>{{ $employees->firstItem() + $index }}</td>
                                <td>{{ $employee->employee_code }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->department->name ?? '-' }}</td>
                                <td>{{ $employee->position->name ?? '-' }}</td>
                                <td>
                                    @if($employee->status == 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($employee->status == 'inactive')
                                        <span class="badge bg-warning text-dark">Nonaktif</span>
                                    @else
                                        <span class="badge bg-danger">Resign</span>
                                    @endif
                                </td>
                                <td>Rp{{ number_format($employee->basic_salary, 0, ',', '.') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-info text-white" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $employee->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <x-modal-confirm id="deleteModal{{ $employee->id }}" action="{{ route('employees.destroy', $employee->id) }}" title="Hapus Karyawan" message="Apakah Anda yakin ingin menghapus karyawan {{ $employee->name }}?" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $employees->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection