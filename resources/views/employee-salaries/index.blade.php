@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Gaji Karyawan' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Gaji Karyawan</h1>
    <div class="card shadow mb-4">
        <div class="card-body border-bottom">
            <form action="{{ route('employee-salaries.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / Kode Karyawan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    @if(request('search'))
                        <a href="{{ route('employee-salaries.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Gaji Pokok</th>
                            <th>Total Tunjangan</th>
                            <th>Total Potongan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->employee_code }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->department->name ?? '-' }}</td>
                                <td>Rp{{ number_format($employee->basic_salary, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($employee->total_earnings ?? 0, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($employee->total_deductions ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('employee-salaries.show', $employee->id) }}" class="btn btn-sm btn-info text-white">Kelola</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Belum ada data</td></tr>
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
