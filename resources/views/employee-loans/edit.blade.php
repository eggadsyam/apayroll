@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pinjaman' => route('employee-loans.index'), 'Edit' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Edit Pinjaman</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('employee-loans.update', $loan->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3"><label class="form-label">Karyawan</label><input type="text" class="form-control" value="{{ $loan->employee->name }}" readonly></div>
                <div class="mb-3"><label class="form-label">Tanggal <span class="text-danger">*</span></label><input type="date" class="form-control" name="loan_date" value="{{ old('loan_date', $loan->loan_date ? $loan->loan_date->format('Y-m-d') : '') }}" required></div>
                <div class="mb-3"><label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label><input type="number" class="form-control" name="amount" value="{{ $loan->amount }}" required></div>
                <div class="mb-3"><label class="form-label">Total Tenor <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_installments" value="{{ $loan->total_installments }}" required></div>
                <div class="mb-3"><label class="form-label">Cicilan (Rp) <span class="text-danger">*</span></label><input type="number" class="form-control" name="installment_amount" value="{{ $loan->installment_amount }}" required></div>
                <div class="mb-3"><label class="form-label">Keterangan</label><textarea class="form-control" name="notes" rows="3">{{ $loan->notes }}</textarea></div>
                <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
