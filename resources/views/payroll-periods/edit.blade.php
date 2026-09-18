@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Periode' => route('payroll-periods.index'), 'Edit' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Edit Periode Penggajian</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('payroll-periods.update', $period->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3"><label class="form-label">Nama Periode <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" value="{{ $period->name }}" required></div>
                <div class="row mb-3">
                    <div class="col-md-6"><label class="form-label">Bulan <span class="text-danger">*</span></label><select class="form-select" name="month" required>@for($i=1; $i<=12; $i++) <option value="{{ $i }}" {{ $period->month == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor</select></div>
                    <div class="col-md-6"><label class="form-label">Tahun <span class="text-danger">*</span></label><select class="form-select" name="year" required>@for($i=2020; $i<=2030; $i++) <option value="{{ $i }}" {{ $period->year == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor</select></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><label class="form-label">Mulai <span class="text-danger">*</span></label><input type="date" class="form-control" name="start_date" value="{{ old('start_date', $period->start_date ? $period->start_date->format('Y-m-d') : '') }}" required></div>
                    <div class="col-md-4"><label class="form-label">Selesai <span class="text-danger">*</span></label><input type="date" class="form-control" name="end_date" value="{{ old('end_date', $period->end_date ? $period->end_date->format('Y-m-d') : '') }}" required></div>
                    <div class="col-md-4"><label class="form-label">Tgl Bayar <span class="text-danger">*</span></label><input type="date" class="form-control" name="payment_date" value="{{ old('payment_date', $period->payment_date ? $period->payment_date->format('Y-m-d') : '') }}" required></div>
                </div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
