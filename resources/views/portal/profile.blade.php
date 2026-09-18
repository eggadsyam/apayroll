@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Portal Karyawan' => route('portal.dashboard'), 'Profil' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Profil Karyawan</h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            @if($employee)
            <div class="row">
                <div class="col-md-3 font-weight-bold">Nama</div>
                <div class="col-md-9">{{ $employee->name }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 font-weight-bold">Kode Karyawan</div>
                <div class="col-md-9">{{ $employee->employee_code }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 font-weight-bold">Departemen</div>
                <div class="col-md-9">{{ $employee->department->name ?? '-' }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 font-weight-bold">Jabatan</div>
                <div class="col-md-9">{{ $employee->position->name ?? '-' }}</div>
            </div>
            @else
            <div class="alert alert-warning">Data karyawan tidak ditemukan.</div>
            @endif
        </div>
    </div>
</div>
@endsection
